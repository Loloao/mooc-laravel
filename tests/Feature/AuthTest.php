<?php
namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\UserServices;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testRegister()
    {
        $response = $this->post('wx/auth/register', [
            'username' => "tanfan2",
            'password' => "123456",
            'mobile'   => '13111111112',
            'code'     => '1234',
        ]);
        $response->assertStatus(200);
        $res = $response->getOriginalContent();
        $this->assertEquals(0, $res['errno']);
        $this->assertNotEmpty($res['data']);
    }

    public function testRegisterMobile()
    {
        $response = $this->post('wx/auth/register', [
            'username' => "tanfan2",
            'password' => "123456",
            'mobile'   => '1311111111112',
            'code'     => '1234',
        ]);
        $response->assertStatus(200);
        $res = $response->getOriginalContent();
        $this->assertEquals(707, $res['errno']);
    }

    public function testRegCaptcha()
    {
        $response = $this->post('wx/auth/regCaptcha', [
            'mobile' => '13111111112',
        ]);
        $response->assertJson(['errno' => 0, 'errmsg' => '成功']);
    }

    public function testLogin()
    {
        $response = $this->json('post', 'wx/auth/login', [
            'username' => 'tanfan',
            'password' => '123123',
        ]);

        $response->assertJson([
            'errno'  => 0,
            'errmsg' => '成功',
            "data"   => [
                "userInfo" => [
                    'nickName' => 'tanfan',
                ],
            ],
        ]);

        $this->assertNotEmpty($response->getOriginalContent()["data"]['token'] ?? '');
    }

    public function testUser()
    {

        $response  = $this->post('wx/auth/login', ['username' => 'tanfan', 'password' => '123123']);
        $token     = $response->getOriginalContent()['data']['token'] ?? '';
        $response2 = $this->json('get', '/wx/auth/info', [], ['Authorization' => 'Bearer ' . $token]);

        $user = UserServices::getInstance()->getByUserName('tanfan');
        $response2->assertJson([
            "data" => [
                'nickName' => $user->nickname,
                'avatar'   => $user->avatar,
                'gender'   => $user->gender,
                'mobile'   => $user->mobile,
            ],
        ]);
    }

    public function testLogout()
    {

        $response  = $this->post('wx/auth/login', ['username' => 'tanfan', 'password' => '123123']);
        $token     = $response->getOriginalContent()['data']['token'] ?? '';
        $response2 = $this->getJson('wx/auth/info', ['Authorization' => 'Bearer ' . $token]);

        $user = UserServices::getInstance()->getByUserName('tanfan');
        $response2->assertJson([
            "data" => [
                'nickName' => $user->nickname,
                'avatar'   => $user->avatar,
                'gender'   => $user->gender,
                'mobile'   => $user->mobile,
            ],
        ]);

        $response3 = $this->post('wx/auth/logout', [], ['Authorization' => 'Bearer ' . $token]);
        $response3->assertJson(['errno' => 0]);

        Auth::guard('wx')->forgetUser();
        $response4 = $this->json('get', 'wx/auth/info', ['Authorization' => 'Bearer ' . $token]);
        $response4->assertJson(['errno' => 501]);
    }

    public function testReset()
    {
        $mobile   = '15100000000';
        $code     = UserServices::getInstance()->setCaptcha($mobile);
        $response = $this->post('wx/auth/reset', ['mobile' => '15100000000', 'password' => 'user1234', 'code' => $code]);
        $response->assertJson(['errno' => 0]);
        $user   = UserServices::getInstance()->getByMobile($mobile);
        $isPass = Hash::check('user1234', $user->password);
        $this->assertTrue($isPass);
    }

    public function testProfile()
    {
        $response = $this->post('wx/auth/login', ['username' => 'tanfan', 'password' => '123123']);
        $token    = $response->getOriginalContent()['data']['token'] ?? '';
        $response = $this->post("wx/auth/profile", [
            'avatar'   => '',
            'gender'   => 1,
            'nickname' => 'user1234',
        ], ['Authorization' => 'Bearer ' . $token]);
        $response->assertJson(['errno' => 0]);
        $user = UserServices::getInstance()->getByUserName('tanfan');
        $this->assertEquals('user1234', $user->nickname);
        $this->assertEquals(1, $user->gender);

    }
}