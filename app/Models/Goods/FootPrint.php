<?php
namespace App\Models\Goods;

use App\Models\BaseModel;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FootPrint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FootPrint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FootPrint query()
 * @mixin \Eloquent
 */
class FootPrint extends BaseModel
{
    protected $fillable = [
        'user_id',
        'goods_id',
    ];

}
