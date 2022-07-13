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

    /** @test */
    public function store_記事にコメントを追加できること()
    {
        $article = Article::factory()->for($this->user, 'author')->create();
        $commentData = [
            'body' => '面白かった',
        ];

        $response = $this->actingAs($this->user)->postJson($this->commentsPath($article), ['comment' => $commentData]);

        $response->assertCreated();
        $this->assertDatabaseCount('comments', 1);
    }

    /** @test */
    public function destroy_コメントを削除できること()
    {
        $article = Article::factory()->for($this->user, 'author')->create();
        $comment = Comment::factory()->for($this->user, 'author')->for($article)->create();

        $response = $this->actingAs($this->user)->deleteJson($this->commentPath($article, $comment));

        $response->assertOk();
        $this->assertDatabaseCount('comments', 0);
    }

    /** @test */
    public function destroy_自分以外のコメントは削除できないこと()
    {
        $article = Article::factory()->for($this->user, 'author')->create();
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->for($otherUser, 'author')->for($article)->create();

        $response = $this->actingAs($this->user)->deleteJson($this->commentPath($article, $comment));

        $response->assertForbidden();
    }

    private function commentsPath(Article $article): string
    {
        return "/api/articles/{$article->slug}/comments";
    }

    private function commentPath(Article $article, Comment $comment): string
    {
        $commentsPath = $this->commentsPath($article);
        return "{$commentsPath}/{$comment->id}";
    }
}
