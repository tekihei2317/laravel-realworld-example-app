<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    private $basePath = '/api/articles';
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function store_記事を登録できること()
    {
        $article = [
            'title' => 'type-challengesのオンラインジャッジを作りました',
            'description' => 'テスト',
            'body' => 'type-challengesのオンラインジャッジを作ったので、紹介したいと思います。',
        ];

        $response = $this->actingAs($this->user)->postJson($this->basePath, ['article' => $article]);

        $response->assertCreated();
        $this->assertDatabaseCount('articles', 1);
    }
}
