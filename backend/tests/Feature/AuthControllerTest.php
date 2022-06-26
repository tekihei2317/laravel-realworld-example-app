<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $basePath = '/api/users';

    public function test_ユーザーを登録できること()
    {
        $data = [
            'username' => 'tekihei2317',
            'email' => 'tekihei2317@example.com',
            'password' => 'password',
        ];

        $this->postJson($this->basePath, $data)->assertCreated();
    }
}
