<?php
namespace App\Models\Promotion;

use App\Models\BaseModel;

/**
 * 
 *
 * @property int $id
 * @property int $goods_id 商品表的商品ID
 * @property string $goods_name 商品名称
 * @property string|null $pic_url 商品图片或者商品货品图片
 * @property string $discount 优惠金额
 * @property int $discount_member 达到优惠条件的人数
 * @property string|null $expire_time 团购过期时间
 * @property int|null $status 团购规则状态，正常上线则0，到期自动下线则1，管理手动下线则2
 * @property \Illuminate\Support\Carbon $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property int|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules whereDiscountMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules whereExpireTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules whereGoodsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules wherePicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrouponRules whereUpdateTime($value)
 * @method static \Database\Factories\Promotion\GrouponRulesFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class GrouponRules extends BaseModel
{

}
