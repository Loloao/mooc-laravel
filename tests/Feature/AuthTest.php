<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
            'mobile' => '13111111112',
            'code' => '1234'
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
            'mobile' => '1311111111112',
            'code' => '1234'
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
}
