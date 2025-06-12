<?php
namespace App\Models\Order;

use App\Models\BaseModel;

/**
 * 
 *
 * @property int $id
 * @property int|null $user_id 用户表的用户ID
 * @property int|null $goods_id 商品表的商品ID
 * @property string|null $goods_sn 商品编号
 * @property string|null $goods_name 商品名称
 * @property int|null $product_id 商品货品表的货品ID
 * @property string|null $price 商品货品的价格
 * @property int|null $number 商品货品的数量
 * @property string|null $specifications 商品规格值列表，采用JSON数组格式
 * @property int|null $checked 购物车中商品是否选择状态
 * @property string|null $pic_url 商品图片或者商品货品图片
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property int|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereGoodsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereGoodsSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart wherePicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereSpecifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUserId($value)
 * @mixin \Eloquent
 */
class Cart extends BaseModel
{
    protected $casts = [
        'checked' => 'boolean',
    ];
}
