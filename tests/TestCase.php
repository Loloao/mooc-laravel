<?php
namespace Tests;

use App\Models\User\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /** @var User */
    protected $user;
    protected function setUp(): void
    {
        try {
        parent::setUp();
        } catch(\Exception $e) {
            echo $e->getMessage();
            throw $e;
        }
        $this->user = User::factory()->create();
    }
    public function getAuthHeader($username = 'tanfan', $password = '123123')
    {

        $response = $this->post('wx/auth/login', ['username' => $username, 'password' => $password]);
        $token    = $response->getOriginalContent()['data']['token'] ?? '';
        return ['Authorization' => "Bearer {$token}"];
    }
}