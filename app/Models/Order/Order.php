<?php
namespace App\Models\Order;

use App\Models\BaseModel;

/**
 * 
 *
 * @property int $id
 * @property int $user_id 用户表的用户ID
 * @property string $order_sn 订单编号
 * @property int $order_status 订单状态
 * @property int|null $aftersale_status 售后状态，0是可申请，1是用户已申请，2是管理员审核通过，3是管理员退款成功，4是管理员审核拒绝，5是用户已取消
 * @property string $consignee 收货人名称
 * @property string $mobile 收货人手机号
 * @property string $address 收货具体地址
 * @property string $message 用户订单留言
 * @property string $goods_price 商品总费用
 * @property string $freight_price 配送费用
 * @property string $coupon_price 优惠券减免
 * @property string $integral_price 用户积分减免
 * @property string $groupon_price 团购优惠价减免
 * @property string $order_price 订单费用， = goods_price + freight_price - coupon_price
 * @property string $actual_price 实付费用， = order_price - integral_price
 * @property string|null $pay_id 微信付款编号
 * @property string|null $pay_time 微信付款时间
 * @property string|null $ship_sn 发货编号
 * @property string|null $ship_channel 发货快递公司
 * @property string|null $ship_time 发货开始时间
 * @property string|null $refund_amount 实际退款金额，（有可能退款金额小于实际支付金额）
 * @property string|null $refund_type 退款方式
 * @property string|null $refund_content 退款备注
 * @property string|null $refund_time 退款时间
 * @property string|null $confirm_time 用户确认收货时间
 * @property int|null $comments 待评价订单商品数量
 * @property string|null $end_time 订单关闭时间
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property int|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereActualPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAftersaleStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereConfirmTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereConsignee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCouponPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereFreightPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereGoodsPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereGrouponPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIntegralPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePayTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRefundAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRefundContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRefundTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRefundType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShipChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShipSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShipTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends BaseModel
{
}
