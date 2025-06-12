<?php
namespace App\Services\Order;

use App\CodeResponse;
use App\Enums\OrderEnums;
use App\Inputs\OrderSubmitInput;
use App\Models\Order\Order;
use App\Models\Order\OrderGoods;
use App\Services\BaseServices;
use App\Services\Promotion\CouponServices;
use App\Services\Promotion\GrouponServices;
use App\Services\SystemServices;
use App\Services\User\AddressServices;
use Log;
use Str;

class OrderServices extends BaseServices
{
    /**
     * 提交订单
     * @param mixed $userId
     * @param \App\Inputs\OrderSubmitInput $input
     */
    public function submit($userId, OrderSubmitInput $input)
    {
        if (! empty($input->grouponRulesId)) {
            GrouponServices::getInstance()->checkGrouponValid($userId, $input->grouponRulesId);
        }

        $address = AddressServices::getInstance()->getAddress($userId, $input->addressId);
        if (empty($address)) {
            return $this->throwBadArgumentValue();
        }

        // 获取购物车的商品列表
        $checkedGoodsList = CartServices::getInstance()->getCheckedCartList($userId(), $input->cartId);

        // 计算订单总金额
        $grouponPrice      = 0;
        $checkedGoodsPrice = CartServices::getInstance()->getCartPriceCutGroupon($checkedGoodsList, $input->grouponRulesId, $grouponPrice);

        // 获取优惠券面额
        $couponPrice = 0;
        if ($input->couponId > 0) {
            $coupon     = CouponServices::getInstance()->getCoupon(($input->couponId));
            $couponUser = CouponServices::getInstance()->getCouponUser(($input->userCouponId));
            $is         = CouponServices::getInstance()->checkCouponAndPrice($coupon, $couponUser, $checkedGoodsPrice);
            if ($is) {
                $couponPrice = $coupon->discount;
            }
        }

        // 运费
        $freightPrice = $this->getFreight($checkedGoodsPrice);

        $orderTotalPrice = bcadd($checkedGoodsPrice, $freightPrice);
        $orderTotalPrice = bcsub($orderTotalPrice, $couponPrice);
        $orderTotalPrice = max(0, $orderTotalPrice);

        $order                = Order::new ();
        $order->user_id       = $userId;
        $order->order_sn      = $this->generateOrderSn();
        $order->order_status  = OrderEnums::STATUS_CREATE;
        $order->consignee     = $address->name;
        $order->mobile        = $address->tel;
        $order->address       = $address->province . $address->city . $address->county . " " . $address->address_detail;
        $order->message       = $input->message;
        $order->goods_price   = $checkedGoodsPrice;
        $order->freight_price = $freightPrice;
        $order->coupon_price  = $couponPrice;
        $order->order_price   = $orderTotalPrice;
        $order->actual_price  = $orderTotalPrice;
        $order->groupon_price = $grouponPrice;
        $order->save();

        // 写入购物车商品记录
        $this->saveOrderGoods($checkedGoodsList, $order->id);

        // 清理购物车记录
        CartServices::getInstance()->clearCartGoods($userId, $input->cartId);

        // 减库存
        $this->reduceProductStoce($checkedGoodsList);

        // 添加团购记录
        GrouponServices::getInstance()->openOrJoinGroupon($userId, $order->id, $input->grouponRulesId, $input->grouponLinkId);

        // todo 设置超时任务
    }

    public function reduceProductStoce($goodsList)
    {
        // todo
    }

    /**
     * @param \App\Models\Order\Cart[] $checkedGoodsList
     * @param mixed $orderId
     */
    private function saveOrderGoods($checkedGoodsList, $orderId)
    {
        foreach ($checkedGoodsList as $cart) {
            $orderGoods                 = OrderGoods::new ();
            $orderGoods->order_id       = $orderId;
            $orderGoods->goods_id       = $cart->goods_id;
            $orderGoods->goods_sn       = $cart->goods_sn;
            $orderGoods->product_id     = $cart->product_id;
            $orderGoods->goods_name     = $cart->goods_name;
            $orderGoods->pic_url        = $cart->pic_url;
            $orderGoods->price          = $cart->price;
            $orderGoods->number         = $cart->number;
            $orderGoods->specifications = $cart->specifications;
            $orderGoods->save();
        }
    }

    /**
     * 生产订单编号
     * @return string
     * @throws \App\Exceptions\BusinessException
     */
    public function generateOrderSn()
    {

        return retry(5, function () {
            $orderSn = date('YmdHis') . Str::random(6);
            if (! $this->isOrderSnUsed($orderSn)) {
                return $orderSn;
            }
            Log::warning('订单号获取失败，orderSn' . $orderSn);
            $this->throwBusinessException(CodeResponse::FAIL, '订单号获取失败');
        });
    }

    public function isOrderSnUsed($orderSn)
    {
        return Order::query()->where('order_sn', $orderSn)->exists();
    }

    public function getFreight($price)
    {

        // 算运费
        $freightPrice = 0;
        $freightMin   = SystemServices::getInstance()->getFreightMin();
        if (bccomp($freightMin, $price) == 1) {
            $freightPrice = SystemServices::getInstance()->getFreightValue();
        }
        return $freightPrice;
    }
}
