# laravel

## 项目目录结构

### app

app 是应用程序的主逻辑所在的地方，包含控制器、中间件和模型。里面的文件会定义一些`namespace`，`laravel`和这个应用程序目录使用了一个名为
PSR 4 的加载标准。通常情况下，php 需要使用`require`和`include`来引入类，但是现在只需要使用 use 语句从其他命名空间导入。现在
PSR 4 标准只是在一个特定的目录中定义。可以在根目录下的 composer.json 中的`autoload`配置项配置

- `/app/http/request`: 创建请求类。`authorize`类表示用户是否有权限调用这个请求
- `/app/http/controllers`: controller 类，用于存放处理路由的逻辑

### bootstrap

包含一些缓存和优化，Laravel 会自动生成，以使其运行得更快

### config

下面有很多自定义设置

- `database.php` 数据库连接，邮件服务器，缓存

### database

包含所有与数据库相关的文件，包括更改数据库表模式的迁移，以及`DatabaseSeeder.php`。

- `factories`: 定义了映射到数据库表的模型应该如何为这些模型产生一些假数据
- `migrations`: 迁移的动作都会保存到这里，可以通过引用这里的类来进行回退或者前进操作。在类里的`up`和`down`方法可以进行额外的定制
- `seeders`: 将数据加载进数据库中
    - `DatabaseSeeder.php`: 设置 seeder 运行的顺序

### public

充当 Web 服务器文档路由，包含所有可公开访问的资产，包括一个 index.php，应用的入口

### resources

- `views`: 保存`BladeTemplate`模板文件，在路由文件中通过`view`函数调用
- `js`: 通常用于前端资源，比如 vue.js 组件

### routes

路由，定义应该为每个路由呈现是什么作为响应

### storage

用于存储生成的文件，如日志，一些缓存数据和文件上传

- `/storage/framework/sessions`: session 生成位置。可以设置 session 生成的方式，在`session.php`中配置，默认是 file
  就会生成在这个文件夹中，可以使用 redis 来存储。如果应用程序很大，需要在不同服务器中共享会话就得使用 redis 而不能使用 file
  存储

### tests

测试目录，包含应用程序测试套件，包括单元和特性测试

### vendor

由 composer 管理，不应该修改这个目录中的任何东西

### composer.json

- `require`和`require-dev`: 包含了开发依赖和生产依赖
- `scripts` 包含了所有可以运行的脚本，`artisan key:generate --ansi`为 Laravel 应用程序又有安全相关任务生成安全密钥

### Providers

通过添加一些通用的东西到应用程序，相当于配置应用程序应该如何行为.

- `RouteServiceProvider.php`: 这个路由服务提供程序允许配置路由在 Laravel 应用程序中的工作方式

## 功能介绍

### 路由

- 单个路由配置

```php
Route::get('/greet/{name}', function(name) { // 读取路由参数
  return redirect()->route(name)
})->name('greet') // 设置路由名称，项目变很大时会喜欢使用路由名称

Route::fallback(function() { // 没路由匹配时匹配这个路由
  return view('index', [ // 调用模板文件，可以从 resources/views 里调用
    'name' => 'jack', /// 模板中的变量
  ])
})
```

- 路由模型绑定：当 laravel 根据路由定义中的类型提示变量名自动解析模型实例

```php
// 为参数添加 Task，为路径的变量改为 task 之后，laravel 能够自动加载 task model
// 这里的路由里的 task 会默认通过主键 sql，配置对应的索引键在 Task Model 类的 getRouteKeyName 方法的返回中
Route::get('/tasks/edit/{task}', function (Task $task) {
    return view('edit', ['task' => $task]);
})->name('tasks.show');
```

**[Nested Resources](https://laravel.com/docs/11.x/controllers#restful-nested-resources)**
这个方法可以通过约定来设置相关联资源的路由

```php
// 通过 books.reviews 来设置 /books/{bookId}/reviews/{reviewId}
Route::resource('books.reviews', ReviewController::class)
    ->scoped(['review' => 'book'])
    // 只需要两种操作的路由
    ->only(['create', 'store']);
```

**[Middleware](https://laravel.com/docs/11.x/middleware#main-content)**
中间件会在请求发送和接收时进行拦截并进行对应操作，根据 views 和 api 配置不同类型的中间件

- `ThrottleRequests.php`: 通过使用 limiter 类自动限制特定路由在给定时间范围内使用某些标准运行的次数
- [Laravel Sanctum](https://laravel.com/docs/11.x/sanctum#main-content)
  Sanctum 会尝试在 session 和 cookie 中找到一些用户身份验证细节。而如果是 api 令牌，则 sanctum 总是在授权报头中查找令牌
  如果不想在设置每个路由时都加上中间件，则可以在 controller 中加上中间件
  ```php
  // EventController
  public function _construct() {
      // resource controller 中除了 index 和 show 路由都需要进行用户认证
      $this->middleware('auth:sanctum')->except(['index', 'show'])
  }
  ```

### Blade Templates

Blade 是 Laravel 的模板引擎

```PHP
@isset($name) // 使用指令判断是否定义了某个变量，出自于 php 的 isSet
The name is : {{ $name }}
@endisset
```

- `@csrf`: csrf 中间件保护用户不受 csrf 攻击，laravel
  将生成一个特殊的令牌，对于每个表单的提交都是唯一的，然后会在下一个请求中自动验证来自表单的数据。自动检查表单中是否包含令牌，以及它是否与
  laravel 之前生成的令牌相同。如果表单中没有这个指令，那么会报 419 错误，所以这个指令在表单中是必须的
- `$errors`: laravel 为所有视图提供的特殊错误变量值，比如表单验证错误就会在设置的区域展示，读取的是 session 中记录的错误信息
- `@errors`: 在这个错误指令中，可以访问一个名为 message 的特殊变量，代表这个 label 对应的错误信息

```php
@error('title')
<P>{{ $message }}</P>
@enderror
```

- `@method('PUT')`: 在表单中将数据以 put 方法发送给后端，这个被称为方法欺骗
- `old($name, $value)`: 此方法能够在表单验证失败时保持表单已经输入的值，但是不能处理 method 是 get
  的表单。如果是密码这种敏感信息不建议使用这个方法，第二个值为默认值
- `@include`: 重用子视图
- 展示分页，laravel 可以自动生成页面的链接，同时也会自动读取这个页面的参数，计算出需要在某个页面上显示的数据

```php
@if ($tasks->count())
    {{ $tasks->links() }}
@endif
```

- `@php`: 增加一些不适合放在其他板块比如 model 的 php 代码
- 表单上的`@checked`可以给标签加上`checked`属性
- `@can`: 可以使用 policy 来校验权限

**[component](https://laravel.com/docs/11.x/blade#components)**

- blade 组件可以使用 `x-`开头来进行调用
- 在 component 类构造函数中定义的 public 属性相当于定义 props

### [Migration]()

### [Commands](https://laravel.com/docs/11.x/packages#commands)

- `php artisan route:list`: 展示所有配置的路由
- `php artisan migrate`: 会根据`database/migrate`中的文件对数据库进行对应的操作。我们可以修改`migrate`中的文件之后运行这个命令来修改或是创建
  table。同时 laravel 会在 table 中创建一个 migrate 表，它会包含所有已经运行的迁移的名称，所以它不会进行两次相同的迁移
- `php artisan migrate:rollback`: 回退上一次的移植操作
- `php artisan make:model Task -m`: 创建一个 model 并生成一个迁移文件。
    - `-mf`: 同时创建迁移文件和工厂类
- `php artisan make:factory TaskFactory --model=Task`: 创建模型工厂
- `php artisan db:seed`: 生成假数据并加载进数据库中
- `php artisan migrate:refresh --seed`: 回滚所有迁移并再次运行，同时完全重新创建数据库并只会用 seeder 创建数据
- `php artisan tinker`: laravel 应用程序的命令行界面，允许编写查询，查看结果
- `php artisan make:request TaskRequest`: 创建 request 类
- `php artisan make:controller PhotoController --resource`: 创建 resource controller 类
- `php artisan make:component StarRating`: 创建 component
- `php artisan make:provider StarRating`: 创建 provider
- `php artisan make:policy PostPolicy --model=post`: 创建和特定 model 相关的 policy
- `php artisan make:command SendEventReminders`: 创建命令，创建的命令会在`app/Console/Commands/`文件夹中。所有自动生成的命令都使用
  app 前缀，以突出它是应用程序的自定义命令
- `php artisan schedule:list`: 查看所有定时任务
- `php artisan make:notification EventReminderNotification`: 创建通知
- `php artisan make:migration AddCvPathToJobApplicationsTable`: laravel 可以根据`JobApplicationsTable`自动创建一个针对
- `php artisan make:test AuthTest --unit`: 创建一个 unit test，如果没有 `--unit` 会放置到`tests/Feature`里
- `php artisan make:exception BusinessException`: 创建异常类

### database

当我们创建 model 时，laravel 会踩猜测创建表的名称并且会为表的行自动创建`id`、`created_at`、`updated_at`
字段，可以点进创建的类看实现方法。手动创建 Model 时，如果想用到 factory，则需要`useFactory`，使用 artisan 则不需要手动加上

可以通过使用称为第一模型工厂的工具组合来为模型提供假数据，之后进行 seeding，将其加载到数据库中。

`/database/factories`中的模型工厂提供了一种为 Laravel
中的数据库生成假数据的方法。他们允许我们快速填充数据库中的一些样本数据用于测试或开发目的。工厂类的命名方法为`modelName + Factory`
，比如`UserFactory`，所以我们不需要告诉 laravel 是哪个 model 的工厂类

`/database/seeders`中的类会生成假数据并加载进数据库中，而且总是将生成的数据添加到现有数据之上

model 可以调用`find`或`findFail`这样直接获取查询结果，也可以通过`latest`
等方法通过返回[查询构造器](https://laravel.com/docs/11.x/queries)的实例以面向对象的方式查询 sql，`get`方法来执行查询并获取结果

model 外键在 migration 中配置，

```php
// migration
// 当从数据库中删除图书记录时，所有相关评论也会被删除
// 外键以及外键关系，下面的两句注释和第三句功能一致，第三句相当于简写
// $table->unsignedBigInteger('book_id');
// $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
$table->foreignId('book_id')->constrained()->cascadeOnDelete();

// model
// book
public function reviews() {
    $this->hasMany(Review::class)
}
// reviews
$this->belongsTo(Book::class)
// 创建 reviews 时
$book->reviews().create($data)
```

`lazy loading(延迟加载)`：取出所有数据
`eager loading(渴望加载)`： 在一个查询中检索部分外键数据，对于大型数据检索并向提高性能或者当你知道必须访问相关数据时，渴望加载更好。同时如果想最小化对数据库运行的查询量，也应该使用渴望加载

**table 方法**
`paginate(perPage)`: 正常翻页
`simplePaginate(perPage)`: 加载下一页，不用返回 total，适用于大数据

`where('id', 1)`: 返回一个符合条件的数组

- `where('id', 1)->dump()`: 展示 sql 语句
- `where('id', 1)->orWhere('name', 'like', 'tan%')`: or 语句

`find(1)`: 找到主键为 1 的对象
`pluck(colName)`: 返回列的数据
`take(2)`: 返回前两个数据
`max(colName)`: 返回这一列最大的数据
`min(colName)`: 返回这一列最小的数据
`avg(colName)`: 返回这一列平均数
`count(colName)`: 这一列的数量
`sum(colName)`: 这一列数字之和
`exists(colName)`: 是否存在

**[local query scope](https://laravel.com/docs/11.x/queries#main-content)**
开发可以不需要知道某个查询的具体细节，只需要调用这个查询即可

```php
// book model
// 方法名必须使用 scope 驼峰形式
public function scopeTitle(Builder $query, string $title): Builder
{
    return $query->where('title', 'LIKE', '%'+$title . '%');
}

// 定义之后使用，可以在 Tinker 调用来看结果
Book::title('titleWord')->get()
```

- `\App\Models\Book:withCount('reviews')->get()`: 会在结果加上一个字段`review_count`设置为 review 的数量
- `\App\Models\Book:withAvg('reviews', 'rating')->having('reviews_count', '>=', 10)->orderBy('reviews_avg_rating')->get()`:
  会为每个结果添加一个`review_avg_rating`字段，表示 rating 的平均值。当使用聚合查询时，需要使用`having`

每个查询都可以在最后加上`toSql`方法来查看 Laravel 运行的实际 SQL 查询

- query 过滤器

```php
public function scopePopular(Builder $query, $from = null, $to = null): Builder
{
    return $query->withCount([
        'reviews' => function (Builder $q) use ($from, $to) {
            if ($from && !$to) {
                $q->where('created_at', '>=', $from);
            } elseif (!$from && $to) {
                $q->where('created_at', '<=', $to);
            } elseif ($from && $to) {
                $q->where('created_at', [$from, $to]);
            }
        },
    ])->orderBy('reviews_count', 'desc');
}
```

- 使用外键进行查询时，需要使用`has`，`whereHas`进行查询

- 当 title 字段时不为 null 或 undefined 时，调用 query 进行查询

```php
Book::when($title, fn($query, $title) => $query->title($title))->get()
```

**[API Resources](https://laravel.com/docs/11.x/eloquent-resources#main-content)**
Api Resources 主要用于将 Model 数据结构转换成返回给客户端的数据结构，它比 Model 自己的 toJson 提供更健壮和更高颗粒度的应用

返回`{data: .., meta: ..}`格式的数据结构

```php
public function index()
{
    return EventResource::collection(Event::all());
}
```

- `whenload`: 当依赖关系 load 时才会返回对应字段内容

```php
// 定义
public function toArray(Request $request): array
{
    return [
        // 加载一个 user
        'user' => new UserResource($this->whenLoaded('user')),
        // 加载多个 attendee
        'attendees' => AttendeeResource::collection($this->whenLoaded('attendees')),
    ];
}
// 使用以下方式时会调用
public function index()
{
    return EventResource::collection(Event::with('user')->get());
}
```

session 也是一种 resource，也可以通过创建 api resource 来进行控制

- 可以创建相关联的 sql 数据

```php
// 创建一个和事件相关联的 参与者
$attendee = $event->attendees()->create([
    'user_id' => 1,
]);
```

- 进行计数查询时，需要调用`$job->applications()->count()`而不是`$job->applications->count()`，后者会调用所有 model
  来计算数量，而前者会使用 sql 的 count

**[migrations](https://laravel.com/docs/11.x/migrations#main-content)**
创建外键时需要先创建外键表再用外键进行关联，删除时则需要先删除外键关系在删除表

```php
// 创建时
public function up(): void
{
    Schema::create('employers', function (Blueprint $table) {
        // ...
    });
    Schema::table('jobs', function (Blueprint $table) {
        $table->foreignIdFor(\App\Models\Employer::class)->constrained();
    });
}


// 删除时
public function down(): void {
    Schema::table('jobs', function (Blueprint $table) {
        $table->dropForeignIdFor(\App\Models\Employer::class);
    });
    Schema::dropIfExists('employers');
}
```

如果在后期需要对已存在 table 的添加软删除，可以创建一个新的 migration

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
```

如果需要显示已删除的数据，需要在 query 使用`withTrashed`

```php
'jobs' => auth()->user()->employer
    ->jobs()
    ->with(['employer', 'jobApplications', 'jobApplications.user'])
    ->withTrashed()
    ->get(),
```

之后还需要在 对应 model 使用 trait `use Illuminate\Database\Eloquent\SoftDeletes;`

### [cache](https://laravel.com/docs/11.x/cache#main-content)

不要缓存私密数据

```php
// redis 会查看是否已经包含这个键，如果没有，就会运行回调函数，
// 如果有,在接下来一个小时的所有调用都将返回存储的结果
$cacheKey = 'book:' . $filter . ':' . $title;
cache()->remember($cacheKey, 3600, fn() => $books->get())
// 移除键值
cache()->forget('book: ' . $review->book_id)
```

我们可以通过监听 laravel 自动调用的生命周期模型事件来处理缓存
**[Events](https://laravel.com/docs/11.x/eloquent#events)**
有一些生命周期模型事件，Laravel 会自动调用，无论何时，无论模型发生什么事情

```php
// 只有 model 更新才会调用
// model 使用 updated 方法不会触发，因为 model 上的 update 方法不会首先获取 model 而是调用 query
// laravel 中使用 sql 方法也不会触发
// 如果使用数据库事务，如果事务被回滚，它也可能不会被触发
// 如果只是加载模型，然后通过更改属性来修改它，那么可以确定将被触发
protected static function booted()
{
    static::updated(fn(Review $review) => cache()->forget('book:' . $review->book_id));
}

```

**[Rating Limit](https://laravel.com/docs/11.x/rate-limiting#main-content)**
它使用与 Laravel 中的 cache 的抽象，同时默认使用相同的配置，比如 redis。通常配合中间件在特定的请求中使用。可以应用在限制付费和免费用户访问的场景中

速率限制默认使用缓存的配置，在`cache.php`的`limit`配置项可以进行配置

```php
'default' => env('CACHE_STORE', 'database'),

'limiter' => 'redis',
```

`throttle`中间件可以限制某个时间段内 api 的发送次数，它最适用于面向公共的写操作，也就是创建、更新或删除资源

```php
$this->middle('throttle:60,1')->only(['store', 'only', 'destroy']);
```

### [controller](https://laravel.com/docs/11.x/controllers#main-content)

`/app/http/controller`用于存放处理路由的逻辑。控制器负责将模型和视图粘合在一起以产生最终输出

controller 类中的`__invoke`方法可以让路由只简单调用类，而不是调用方法

**[Resource Controllers](https://laravel.com/docs/11.x/controllers#resource-controllers)**
Laravel resource routing assigns the typical create, read, update, and delete ("CRUD") routes to a controller with a
single line of code.

它给了我们标准化的命名约定，让开发人员更容易理解每个方法和每个控制器的用途，同时还提供了自动路由注册。我们也可以只注册部分资源控制器

在 view 里调用 query 时也会进行延迟加载，我们可以通过`load`方法对搜索结果进行处理

```php
public function show(Book $book)
{
    return view(
        'books.show',
        [
            'book' => $book->load([
                'reviews' => fn($query) => $query->latest(),
            ]),
        ]);
}
```

### [Authenticate](https://laravel.com/docs/11.x/authentication#main-content)

logout 时需要通过`invalidate`方法删除 session 文件，`regenerateToken`则需要重新生成一个 session
用于重新跟踪已经注销的用户之后的行为

```php
public function destroy(string $id)
{
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
}
```

- Controller 中 `auth()->user()`可以访问到当前登录用户的 model

### [Authorization](https://laravel.com/docs/11.x/authorization#gates)

`attempt`方法专门用于登录表单提交验证

```php
Auth::attempt($credentials, $remember)
```

laravel 有两种定义权限的方法 Gate 和 Policies

**[Gate](https://laravel.com/docs/11.x/authorization#gates)**
其中 Gate 可以在 Provider 的 boot 方法中定义，它只会在程序开始时执行一次

```php
// auth provider
public function boot()
{
    Gate::define('update-event', function ($user, Event $event) {
        return $user->id === $event->user_id;
    });
}
```

定义后可以在任何地方通过 gate facade 访问

```php
// event controller
if (Gate::denies('update-event', $event)) {
    abort(403, 'You are not authorized to update this event');
}
// 也可以通过 controller 中的 authorize 方法执行
$this->authorize('update-event', $event);
```

gate 只适用于一些通用权限检查的简单情况

**[Policy](https://laravel.com/docs/11.x/authorization#creating-policies)**
policy 一般用于控制 controller 模型对资源的访问。laravel 会自动将 Policy 方法映射到资源控制器操作，可以为特定模型生成
policy 类，这些类的方法名称与控制器中的操作名称相同，Laravel 可以自动将它们绑定到特定的操作

`php artisan make:policy PostPolicy --model=post`创建 Policy 类，可以在`AuthServiceProvider`的 `$policies`中注册
policy，但这只是想要覆盖默认 laravel 行为时的特殊情况，实际情况是 Laravel
会自动发现策略类，需要类名包括一个模型名称后加上`policy`后缀，最好是使用默认标准

```php
// 需要在 controller 的构造函数上注册
// controller 必须继承自 Illuminate\Routing\Controller
// Controller.php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

// 使用时
public function __construct()
{
    // ...
    $this->authorizeResource(Event::class, 'event');
}
```

使用时如果 policy 找不到对应的 Model 则需要在第二个参数传入 Model

```php
$this->authorize('viewAnyEmployer', Job::class);
```

policy 方法还可返回特定的 response，这里的 deny 会返回到 403 提示文案中

```php
if ($job->jobApplications()->count() > 0) {
    return Response::deny('Cannot change the job with applications');
}
```

### [Schedule](https://laravel.com/docs/11.x/scheduling)

可以通过时间间隔或者特定时间运行命令，所有定时任务都定义在`/routes/console.php`，`php artisan schedule:work`
运行定时任务。而如果在实际部署时则需要增加一个简单`cron`配置每分钟跑一次`schedule:run`
`* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`

### [Notification](https://laravel.com/docs/11.x/notifications#main-content)

基本上来说，email 是一种`delivery channel`，还可以将通知保存在数据库里。`Notification`类有几个方法

- `via`: 定义 delivery channel
- `toMail`: 创建通知的电子邮件表示，通过`new MailMessage`来发送
- `toArray`: 存储和通知有关的信息

更多有关构造邮件的方式请看[这里](https://laravel.com/docs/11.x/mail#main-content)

### [Queue](https://laravel.com/docs/11.x/queues#main-content)

队列允许将一些耗时的任务推迟到后台运行，这会提高应用程序的性能，提高用户体验，使用队列的一个很好的例子就是发送电子邮件，可以告诉
Laravel 稍后再做，然后再服务器上运行一个单独的进程。

每个队列都需要配置一个驱动程序，来知道要做的事情的信息应该存储在哪里。可以通过数据库、redis、Amazon SQS 来存储信息，laravel
提供了统一的 api 来操作

当需要某个操作在 queue 中运行时，使用`ShouldQueue`接口即可。当进行操作时，只是把这个操作推进了 queue 中，它会保存进 database
的 jobs 表中。之后我们需要在服务器上运行一个单独的进程，该进程将直接访问数据库，拾取要运行的任务一个一个执行

`php artisan queue:work` 命令可以启动 queue，当在 linux
等系统中时，需要确保这个命令一直运行。同时还得确保当应用程序部署到服务器时，此命令都将重新启动。为了确保这个命令正在运行并且在中止后会重新启动，最好方法是使用类似于`Supervisor`
的监控工具

### web

- session: 在 web 开发中，session 只是存储特定用户数据的一种方式，session 通常在用户首次访问网站时开始，在关闭或注销网站时结束。在
  laravel 中有一个强大且易于使用的会话系统，可轻松地在应用程序中存储和检索会话数据。开发可以在会话中为用户显式存储不同类型的数据。如果表单验证有错误，laravel
  会存储在 session 中，我们则可以在模板中使用`$errors`将其展示
  k
  它的工作原理是，当你访问你的 laravel 应用程序时，laravel 将生成一个新的会话并分配一些唯一的会话 ID，这个唯一的会话 ID
  将存储在用户浏览器上的 cookie 中，然后在后续访问时自动发送。所以 laravel 可以识别这个用户并从正确的会话中读取数据

- 请求头 Accept 必须设置为 JSON laravel 才知道要返回 JSON 格式数据
- 删除资源时返回 204 表示没有资源可以返回
- 401 表示用户完全没有认证，403 表示用户已经认证，但是没有方位特定资源的权限
- 建议用 delete 方法进行 logout 操作，这样相比于 get 更不容易被伪造攻击，delete 也需要提交表单，虽然 get 更方便
- `<form action="{{ route('job.application.store', $job) }}" method="POST" enctype="multipart/form-data">`: form 标签的
  enctype 属性表示可以上传文件

### File Storage

storage
下的文件都不能进行访问，如果想要访问需要通过命令创建符号链接链接到存储中的公共文件夹，就能使该文件夹所有内容可以访问。比如一个场景，你的简历文件只允许你投过的职位的雇主查看，这时就需要创建特定的控制器为特定端点下的文件提供服务。我们可以添加一个新的硬盘

```php
'private' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'visibility' => 'private',
],
```

使用时

```php
$validatedData = $request->validate([
    'expected_salary' => 'required|min:1|max:1000000',
    // 验证文件类型
    'cv' => 'required|file|mimes:png,jpg',
]);

$file = $request->file('cv');
// 文件保存路径, 会在 private 文件夹下创建 cvs 文件夹，将文件存储在此
$path = $file->store('cvs', 'private');
```

### [Request](https://laravel.com/docs/11.x/requests#main-content)

可以创建 request 类，之后定义 request 的权限，校验等

```php
class JobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric|min:5000',
        ];
    }
}
// controller
// 通过参数类进行调用
public function store(JobRequest $request)
{
    // 通过 validated 方法返回验证后的值
    auth()->user()->employer->jobs()->create($request->validated());
    //..
}
```

### [Collection](https://laravel.com/docs/11.x/collections)

```php
// 通过数组创建集合对象
$collection = collection([1, 2, 3]);
// 通过集合获取数组
$collection->toArray();
$collection->all();
```

**聚合运算**

```php
$collection->count(); // 数量
$collection->sum(); // 数据之和
$collection->max(); // 最大值
$collection->min(); // 最小值
```

**查找判断**

```php
$collection->contains(1) // 是否存在某个值
$collection->has('a1') // 是否存在某个 key
$collection->isEmpty() // 是否为空，不能和数组一样通过 empty(array) 判断
```

**遍历**

```php
$products->each(function ($item) { var_dump($item->id) });
$products->map(function ($item) { return $item->id });
$products->keyBy('id')->toArray(); // 把 id 的值当成 key，值为对象
$products->groupBy('category_id')->toArray(); // 把 category_id 的值当成 key，值为相同的 category_id 的对象组成的数组
$products->filter(function ($item) {return $item->id > 3});
```

**对数组本身进行操作**

```php
$collection->flip()->toArray(); // key 和 value 对调
$collection->reverse()->toArray(); // 数组反向
$collection->sort()->toArray();
$collection->sortBy('price')->toArray();
$collection->sortBy(function($product) {
    return $product->price;
})->toArray();
$collection->combine(['v1', 'v2'])
```

### [Facade](https://laravel.com/docs/11.x/facades#main-content)

门面为服务容器提供了一个静态接口，实际上是服务容器中底层类的静态代理，所有的`Facades`都定义在`Illuminate\Support\Facades`
命名空间下

可以给类和方法做注释来获得代码提示

### [Test](https://laravel.com/docs/11.x/testing#main-content)

单元测试可以代替`postman`来对请求进行测试，它在`tests`文件夹下存在两个文件夹`Feature`和`Unit`，一个用于业务功能也就是集成测试，一个用于单元测试

```php

class AuthTest extends TestCase
{
    // 使用这个代码块可以多次往数据库存入相同的数据
    use DatabaseTransactions;

    public function testRegister()
    {
        $response = $this->post('wx/auth/register', [
            'username' => "tanfan2",
            'password' => "123456",
            'mobile' => '13111111112',
            'code' => '1234'
        ]);
        $response->assertStatus(200);
        $res = $response->getOriginalContent();
        $this->assertEquals(0, $res['errno']);
        $this->assertNotEmpty($res['data']);
    }
}
```

### Error Handling

Exception 类通过`report`方法来控制报错

### Service

可以使用单例模式创建服务，而不需要每次都`new`。

```php
    protect static $instance;

    private function __construct()
    {
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (static::$instance instanceof static) {
            return static::$instance;
        }
        static::$instance = new static();
        return static::$instance;
    }

    private function __clone()
    {
    }
```
