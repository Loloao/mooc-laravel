<?php

namespace App\Http\Controllers\Wx;

use App\CodeResponse;
use App\Models\User\User;
use App\Services\UserServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends WxController implements HasMiddleware
{

    static $only = ['info'];

    public static function middleware(): array
    {
        return [new Middleware('auth:wx', static::$only)];
    }

    public function register(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $mobile = $request->input('mobile');
        $code = $request->input('code');

        if (empty($username) || empty($password) || empty($mobile) || empty($code)) {
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }

        $user = UserServices::getInstance()->getByUserName($username);
        if (!is_null($user)) {
            return $this->fail(CodeResponse::AUTH_NAME_REGISTERED);
        }

        $validator = Validator::make(['mobile' => $mobile], ['mobile' => 'regex:/^1[1-9]{10}$/']);
        if ($validator->fails()) {
            return $this->fail(CodeResponse::AUTH_INVALID_MOBILE);
        }

        $user = UserServices::getInstance()->getByMobile($mobile);
        if (!is_null($user)) {
            return $this->fail(CodeResponse::AUTH_MOBILE_REGISTERED);
        }

        $user = new User();
        $user->username = $username;
        $user->password = Hash::make($password);
        $user->mobile = $mobile;
        $user->avatar = 'https://i.pinimg.com/736x/0d/64/98/0d64989794b1a4c9d89bff571d3d5842.jpg';
        $user->nickname = $username;
        $user->last_login_time = Carbon::now()->toDateTimeString();
        $user->last_login_ip = $request->getClientIp();
        $user->save();

        // todo 新用户发券

        // 返回用户信息和 token
        return $this->success([
            'token' => '',
            'userInfo' => [
                'nickName' => $username,
                'avatarUrl' => $user->avatar,
            ],
        ]);
    }

    public function regCaptcha(Request $request)
    {
        $mobile = $request->input('mobile');
        if (empty($mobile)) {
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }
        $validator = Validator::make(['mobile' => $mobile], ['mobile' => 'regex:/^1[1-9]{10}$/']);
        if ($validator->fails()) {

            return $this->fail(CodeResponse::AUTH_INVALID_MOBILE);
        }

        $user = UserServices::getInstance()->getByMobile($mobile);
        if (!is_null($user)) {
            return $this->fail(CodeResponse::AUTH_NAME_REGISTERED);
        }

        // 防刷验证，一分钟只能发送一次，redis 锁的实现
        // add 方法如果没保存成功会返回 null
        $lock = Cache::add('register_captcha_lock' . $mobile, 1, 60);
        if (!$lock) {
            return $this->fail(CodeResponse::AUTH_CAPTCHA_FREQUENCY);
        }

        $isPass = UserServices::getInstance()->checkMobileSendCaptchaCount($mobile);
        if (!$isPass) {
            return $this->fail(CodeResponse::AUTH_CAPTCHA_UNSUPPORT);
        }

        return $this->success();
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (empty($username) || empty($password)) {
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }

        $user = UserServices::getInstance()->getByUsername($username);
        if (is_null($user)) {
            return $this->fail(CodeResponse::AUTH_INVALID_ACCOUNT);
        }

        $isPass = Hash::check($password, $user->getAuthPassword());
        if (!$isPass) {
            return $this->fail(CodeResponse::AUTH_INVALID_ACCOUNT);
        }

        $user->last_login_time = now()->toDateTimeString();
        $user->last_login_ip = $request->getClientIp();
        if (!$user->save()) {
            return $this->fail(CodeResponse::UPDATED_FAIL);
        }

        $token = $user->createToken(name: $username)->plainTextToken;

        return $this->success([
            'token' => $token,
            'userInfo' => [
                'nickName' => $username,
                'avatarUrl' => $user->avatar,
            ],
        ]);

    }

    public function info()
    {
        $user = Auth::guard('wx')->user();
        return $this->success([
            'nickName' => $user->nickname,
            'avatar' => $user->avatar,
            'gender' => $user->gender,
            'mobile' => $user->mobile,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->success();
    }

    /**
     * 密码重置
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $password = $request->input('password');
        $mobile = $request->input('mobile');
        $code = $request->input('code');

        if (empty($password) || empty($mobile) || empty($code)) {
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }

        // $isPass = UserServices::getInstance()->checkCaptcha($mobile, $code);
        // if (!$isPass) {
        //     return $this->fail(CodeResponse::AUTH_CAPTCHA_UNMATCH);
        // }
        //

        $user = UserServices::getInstance()->getByMobile($mobile);
        if (is_null($user)) {
            return $this->fail(CodeResponse::AUTH_MOBILE_UNREGISTERED);
        }

        $user->password = Hash::make($password);
        $ret = $user->save();
        return $this->failOrSuccess($ret, CodeResponse::UPDATED_FAIL);
    }

    /**
     * 用户信息修改
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $user = Auth::user();
        $avatar = $request->input("avatar");
        $gender = $request->input("gender");
        $nickname = $request->input("nickname");

        if (!empty($avatar)) {
            $user->avatar = $avatar;
        }

        if (!empty($gender)) {
            $user->gender = $gender;
        }

        if (!empty($nickname)) {
            $user->nickname = $nickname;
        }

        $res = $user->save();

        return $this->failOrSuccess($res, CodeResponse::UPDATED_FAIL);
    }

}
