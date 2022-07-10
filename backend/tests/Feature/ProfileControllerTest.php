<?php

namespace Tests\Feature;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $basePath = '/api/profiles';
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function follow_ユーザーをフォローできること()
    {
        $userToFollow = User::factory()->create();

        $response = $this->actingAs($this->user)->postJson($this->profilePath($userToFollow) . '/follow');

        $response->assertOk();
        $this->assertDatabaseCount('follows', 1);
        $this->assertEquals(1, $this->user->followees()->count());
    }

    /** @test */
    public function follow_同じユーザーを2回フォローしても変化しないこと()
    {
        $userToFollow = User::factory()->create();

        $this->actingAs($this->user)->postJson($this->profilePath($userToFollow) . '/follow');
        $response = $this->actingAs($this->user)->postJson($this->profilePath($userToFollow) . '/follow');

        $response->assertOk();
        $this->assertEquals(1, $this->user->followees()->count());
    }

    /** @test */
    public function unfollow_ユーザーのフォローを解除できること()
    {
        $followee = User::factory()->create();
        Follow::factory()->for($this->user, 'follower')->for($followee, 'followee')->create();

        $response = $this->actingAs($this->user)->deleteJson($this->profilePath($followee) . '/follow');

        $response->assertOk();
        $this->assertDatabaseCount('follows', 0);
    }

    public function profilePath(User $user): string
    {
        return "{$this->basePath}/{$user->username}";
    }
}
