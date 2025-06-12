<?php
namespace App\Http\Controllers\Wx;

use App\Inputs\OrderSubmitInput;
use App\Services\Order\OrderServices;
use Illuminate\Support\Facades\DB;

class OrderController extends WxController
{
    /**
     * 提交订单
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BusinessException
     */
    public function submit()
    {
        $input = OrderSubmitInput::new ();
        $order = DB::transaction(function () use ($input) {
            return OrderServices::getInstance()->submit($this->userId(), $input);
        });

        return $this->success([
            'orderId'       => $order->id,
            'grouponLinkId' => $input->grouponLinkId ?? 0,
        ]);
    }
}
