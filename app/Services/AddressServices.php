<?php
namespace App\Services;

use App\CodeResponse;
use App\Models\User\Address;

class AddressServices extends BaseServices
{
    /**
     * 获取地址列表
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAddressListByUserId(int $userId)
    {
        return Address::query()->where('user_id', $userId)->where("deleted", 0)->get();
    }

    /**
     * 获取用户地址
     * @param int $userId
     * @param int $addressId
     * @return Address|null
     */
    public function getAddress(int $userId, int $addressId)
    {
        return Address::query()->where('user_id', $userId)->where('id', $addressId)->where("deleted", 0)->first();
    }

    /**
     * 删除地址
     * @param int $userId
     * @param int $addressId
     * @return bool|null
     */
    public function delete(int $userId, int $addressId)
    {
        $address = $this->getAddress($userId, addressId: $addressId);
        if (is_null($address)) {
            $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
        }
        return $address->delete();
    }

}
