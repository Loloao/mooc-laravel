<?php

namespace App\Http\Controllers\Wx;

use App\Services\AddressServices;
use Illuminate\Support\Facades\Auth;

class AddressController extends WxController
{

    public function list()
    {
        $list = AddressServices::getInstance()->getAddressListByUserId(auth()->user()->id);
        return $this->success([
            'total' => $list->count(),
            'page' => 1,
            'list' => $list->toArray(),
            'pages' => 1,
        ]);
    }

    public function detail()
    {}

    public function save()
    {}

    public function delete()
    {}

}