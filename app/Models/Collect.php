<?php
namespace App\Models;

use App\Models\BaseModel;

/**
 * 
 *
 * @property int $id
 * @property int $user_id 用户表的用户ID
 * @property int $value_id 如果type=0，则是商品ID；如果type=1，则是专题ID
 * @property int $type 收藏类型，如果type=0，则是商品ID；如果type=1，则是专题ID
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property int|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collect newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collect newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collect query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collect whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collect whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collect whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collect whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collect whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collect whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collect whereValueId($value)
 * @mixin \Eloquent
 */
class Collect extends BaseModel
{

    protected $casts = [
        'pic_list' => 'array',
    ];
}
