<?php
namespace App\Models\Goods;

use App\Models\BaseModel;

/**
 * 
 *
 * @property int $id
 * @property string|null $question 问题标题
 * @property string|null $answer 问题答案
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property int|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereUpdateTime($value)
 * @mixin \Eloquent
 */
class Issue extends BaseModel
{

}
