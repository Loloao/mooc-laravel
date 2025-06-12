<?php
namespace App\Http\Controllers\Wx;

use App\Inputs\PageInput;
use App\Services\Promotion\CouponServices;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CouponController extends WxController implements HasMiddleware
{
    static $only = ['myList'];
    public static function middleware(): array
    {
        return [new Middleware('auth:wx', static::$only)];
    }
    /**
     * 优惠券列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $page    = PageInput::new ();
        $columns = ['id', 'name', 'desc', 'tag', 'discount', 'min', 'days', 'start_time', 'end_time'];
        $list    = CouponServices::getInstance()->list($page, $columns);
        return $this->successPaginate($list);
    }

    /**
     * 我的优惠券列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function myList()
    {
        $status         = $this->verifyInteger('status');
        $page           = PageInput::new ();
        $list           = CouponServices::getInstance()->myList($this->user()->id, $status, $page);
        $couponUserList = collect($list->items());
        $couponIds      = $couponUserList->pluck('coupon_id')->toArray();
        $coupons        = CouponServices::getInstance()->getCoupons($couponIds)->keyBy('id');
        $myList         = $couponUserList->map(function ($item) use ($coupons) {
            $coupon = $coupons->get($item->coupon_id);
            return [
                'id'        => $item->id,
                'cid'       => $coupon->id,
                'name'      => $coupon->name,
                'desc'      => $coupon->desc,
                'tag'       => $coupon->tag,
                'min'       => $coupon->min,
                'discount'  => $coupon->discount,
                'startTime' => $item->startTime,
                'endTime'   => $item->endTime,
                'available' => false,
            ];
        });
        $list = $this->paginate($list, $myList->toArray());
        return $this->success($list);
    }

    /**
     * 领取优惠券
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BusinessException
     */
    public function receive()
    {
        $couponId = $this->verifyId('couponId', 0);
        CouponServices::getInstance()->receive($this->userId(), $couponId);
        return $this->success();
    }
}
