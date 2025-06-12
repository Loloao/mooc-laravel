<?php
namespace App\Models;

use App\Models\BaseModel;

/**
 * 
 *
 * @property int $id
 * @property string $key_name 系统配置名
 * @property string $key_value 系统配置值
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property int|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System whereKeyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System whereKeyValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System whereUpdateTime($value)
 * @mixin \Eloquent
 */
class System extends BaseModel
{

}