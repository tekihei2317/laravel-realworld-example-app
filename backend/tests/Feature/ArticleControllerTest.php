<?php

namespace Tests\Feature;

use App\Models\Article;
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
    public function show_記事を取得できること()
    {
        $article = Article::factory()->for($this->user, 'author')->create();

        $response = $this->actingAs($this->user)->getJson($this->basePath . "/{$article->slug}");

        $response->assertOk();
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

    /** @test */
    public function update_記事を更新できること()
    {
        $article = Article::factory()->make();
        $this->user->articles()->save($article);
        $newArticleData = [
            'title' => 'updated',
            'description' => 'updated',
            'body' => 'updated',
        ];

        $response = $this->actingAs($this->user)->putJson($this->articlePath($article), ['article' => $newArticleData]);

        $response->assertOk();
        $this->assertDatabaseHas('articles', $newArticleData);
    }

    /** @test */
    public function destroy_記事を削除できること()
    {
        $article = Article::factory()->for($this->user, 'author')->create();

        $response = $this->actingAs($this->user)->deleteJson($this->articlePath($article));

        $response->assertOk();
        $this->assertModelMissing($article);
    }

    /** @test */
    public function destroy_他の人の記事は削除できないこと()
    {
        $article = Article::factory()->for($this->user, 'author')->create();

        /** @var User */
        $otherUser = User::factory()->create();
        $response = $this->actingAs($otherUser)->deleteJson($this->articlePath($article));

        $response->assertForbidden();
        $this->assertModelExists($article);
    }


    private function articlePath(Article $article): string
    {
        return $this->basePath . "/{$article->slug}";
    }
}
