<?php
namespace App\Services\User;

use App\CodeResponse;
use App\Models\User\Address;
use App\Services\BaseServices;

class AddressServices extends BaseServices
{
    public function getDefaultAddress(int $userId)
    {
        return Address::query()->where('user_id', $userId)->where('is_default', 1)->first();
    }

    /**
     * 获取地址或者返回默认地址
     * @param int $userId
     * @param mixed $addressId
     * @return Address
     * @throws \App\Exceptions\BusinessException
     */
    public function getAddressOrDefault(int $userId, $addressId = null)
    {

        if (empty($addressId)) {
            $address   = AddressServices::getInstance()->getDefaultAddress($userId);
            $addressId = $address->id ?? 0;
        } else {
            $address = AddressServices::getInstance()->getAddress($userId, $addressId);
            if (empty($address)) {
                $this->throwBadArgumentValue();
            }
        }
        return $address;
    }

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