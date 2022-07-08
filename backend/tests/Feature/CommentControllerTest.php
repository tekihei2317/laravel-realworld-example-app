<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function index_記事のコメント一覧が取得できること()
    {
        $article = Article::factory()->for($this->user, 'author')->create();
        Comment::factory(2)->for($this->user, 'author')->for($article)->create(['body' => '面白かった']);

        $response = $this->actingAs($this->user)->getJson($this->commentsPath($article));

        $response->assertOk();
        $response->assertJson([
            'comments' => [
                [
                    'body' => '面白かった',
                ],
                [
                    'body' => '面白かった'
                ]
            ]
        ]);
    }

    private function commentsPath(Article $article): string
    {
        return "/api/articles/{$article->slug}/comments";
    }
}
