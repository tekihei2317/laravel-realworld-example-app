<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $basePath = '/api/users';

    public function test_register_ユーザーを登録できること()
    {
        $data = [
            'username' => 'tekihei2317',
            'email' => 'tekihei2317@example.com',
            'password' => 'password',
        ];

        $this->postJson($this->basePath, $data)->assertCreated();
    }

    public function test_login_ログインできること()
    {
        $user = User::factory()->create();
        $data = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $this->postJson("{$this->basePath}/login", $data)->assertOK();
    }

    public function test_show_ログイン中のユーザーを取得できること()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user)->getJson("/api/user")->assertOk();
    }

    public function test_show_ログインしていない場合認証エラーになること()
    {
        User::factory()->create();

        $this->getJson("/api/user")->assertUnauthorized();
    }

    public function test_update_ログインユーザー情報を更新できること()
    {
        /** @var User */
        $user = User::factory()->create();

        $data = [
            'username' => $user->username,
            'email' => $user->email,
            'image' => 'http://example.com/image.png',
            'bio' => 'bio'
        ];

        $this->actingAs($user)->putJson("/api/user", $data)->assertOK();
        $this->assertDatabaseHas('users', [
            'username' => $data['username'],
            'email' => $data['email'],
            'image' => $data['image'],
            'bio' => $data['bio'],
        ]);
    }
}
