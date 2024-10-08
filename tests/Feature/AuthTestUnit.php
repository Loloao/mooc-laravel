<?php

namespace Tests\Feature;

use App\Services\UserServices;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AuthTestUnit extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function testCheckMobileSendCaptchaCount(): void
    {
        $mobile = '13111111113';
        foreach (range(0, 9) as $i) {
            $isPass = (new UserServices())->checkMobileSendCaptchaCount($mobile);
            $this->assertTrue($isPass);
        }
        $isPass = (new UserServices())->checkMobileSendCaptchaCount($mobile);
        $this->assertFalse($isPass);

        $countKey = 'register_captcha_count_' . $mobile;
        Cache::forget($countKey);
        $isPass = (new UserServices())->checkMobileSendCaptchaCount($mobile);
        $this->assertTrue($isPass);
    }

    public function testCheckCaptcha()
    {
        $mobile = '13111111111';
        $code = (new UserServices())->setCaptcha($mobile);
        $isPass = (new UserServices())->checkMobileSendCaptchaCount($mobile, $code);
        $this->assertTrue($isPass);
    }
}
