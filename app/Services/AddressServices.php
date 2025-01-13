<?php

namespace App\Services;

use App\Models\Address;

class AddressServices extends BaseServices
{

    public function getAddressListByUserId(int $userId)
    {
        return Address::query()->where('user_id', $userId)->where("deleted", 0)->get();
    }
}