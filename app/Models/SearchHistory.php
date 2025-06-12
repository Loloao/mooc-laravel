<?php
namespace App\Models;

/**
 * 
 *
 * @property int $id
 * @property int $user_id 用户表的用户ID
 * @property string $keyword 搜索关键字
 * @property string $from 搜索来源，如pc、wx、app
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property int|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereUserId($value)
 * @mixin \Eloquent
 */
class SearchHistory extends BaseModel
{

    protected $deleted = 'boolean';

    protected $fillable = [
        'user_id',
        'keyword',
        'from',
    ];
}
