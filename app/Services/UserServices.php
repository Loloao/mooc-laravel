<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class UserServices extends BaseServices
{


    public function getByUserName($username)
    {
        return User::query()->where('username', $username)->where('deleted', 0)->first();
    }

    public function getByMobile($mobile)
    {
        return User::query()->where('mobile', $mobile)->where('deleted', 0)->first();
    }

    /**
     * 验证手机号发送验证码是否达到限制条数
     * @param string $mobile
     * @return bool
     */
    public function checkMobileSendCaptchaCount(string $mobile): bool
    {
        $countKey = 'register_captcha_count_' . $mobile;
        if (Cache::has($countKey)) {

            // 当天只能请求 10 次，increment 自行递增
            $count = Cache::increment('register_captcha_count_' . $mobile);

            if ($count > 10) {
                return false;
            }
        } else {
            Cache::put($countKey, 1, now()->diffInSeconds(Carbon::tomorrow()));
        }

        return true;
    }

    /**
     * 设置手机验证码
     * @param string $mobile
     * @return int
     * @throws
     */
    public function setCaptcha(string $mobile)
    {
        $code = random_int(100000, 999999);
        $code = strVal($code);
        Cache::put('register_captcha_' . $mobile, $code, 600);
        return $code;
    }

}
