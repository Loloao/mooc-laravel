<?php
namespace App\Models\Goods;

use App\Models\BaseModel;

/**
 * 
 *
 * @property int $id
 * @property int $goods_id 商品表的商品ID
 * @property string $specifications 商品规格值列表，采用JSON数组格式
 * @property string $price 商品货品价格
 * @property int $number 商品货品数量
 * @property string|null $url 商品货品图片
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property int|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsProduct whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsProduct whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsProduct whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsProduct whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsProduct whereSpecifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsProduct whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsProduct whereUrl($value)
 * @method static \Database\Factories\Goods\GoodsProductFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class GoodsProduct extends BaseModel
{
    protected $casts = [

        'floor_price' => 'float',
    ];
}