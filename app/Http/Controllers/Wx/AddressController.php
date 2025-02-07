<?php
namespace App\Http\Controllers\Wx;

use App\CodeResponse;
use App\Services\User\AddressServices;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class AddressController extends WxController implements HasMiddleware
{

    public static function middleware(): array
    {
        return [new Middleware('auth:wx')];
    }
    public function list()
    {
        $user = Auth::user();
        $list = AddressServices::getInstance()->getAddressListByUserId($user->id);
        return $this->successPaginate($list);
    }

    public function detail(int $id)
    {
        $user = Auth::user();
        $res  = AddressServices::getInstance()->getAddress($user->id, $id);
        return $this->success($res);
    }

    public function save(Request $request)
    {
        $province       = $request->input('province');
        $city           = $request->input('city');
        $country        = $request->input('country');
        $address_detail = $request->input('address_detail');
        $area_code      = $request->input('area_code');
        $postal_code    = $request->input('postal_code');
        $tel            = $request->input('tes');
        $name           = $request->input('name');
        if (! is_null($province)) {

        }
    }

    public function delete(Request $request)
    {
        $id = $request->input('id', 0);
        if (is_null($id) && ! is_numeric($id)) {
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }
        AddressServices::getInstance()->delete(Auth::user()->id, $id);
        return $this->success();
    }

}
