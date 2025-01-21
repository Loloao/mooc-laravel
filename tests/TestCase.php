<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function getAuthHeader()
    {

        $response = $this->post('wx/auth/login', ['username' => 'tanfan', 'password' => '123123']);
        $token = $response->getOriginalContent()['data']['token'] ?? '';
        return ['Authorization' => "Bearer {$token}"];
    }
}