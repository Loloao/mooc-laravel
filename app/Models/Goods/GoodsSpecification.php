<?php
namespace App\Models\Goods;

use App\Models\BaseModel;

/**
 * 
 *
 * @property int $id
 * @property int $goods_id 商品表的商品ID
 * @property string $specification 商品规格名称
 * @property string $value 商品规格值
 * @property string $pic_url 商品规格图片
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property int|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecification whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecification whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecification whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecification wherePicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecification whereSpecification($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecification whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecification whereValue($value)
 * @method static \Database\Factories\Goods\GoodsSpecificationFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class GoodsSpecification extends BaseModel
{
    protected $casts = [
        'floor_price' => 'float',
    ];
}