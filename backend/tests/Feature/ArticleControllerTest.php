<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Favorite;
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
    public function index_記事一覧を取得できること()
    {
        $this->user->articles()->saveMany(Article::factory(30)->make());

        $response = $this->getJson($this->basePath);

        $response->assertOk();
    }

    /** @test */
    public function getFeed_フィードを取得できること()
    {
        $this->user->articles()->saveMany(Article::factory(30)->make());

        $this->actingAs($this->user)->getJson($this->basePath . '/feed')->assertOk();
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

    /** @test */
    public function favorite_記事をお気に入りできること()
    {
        $article = Article::factory()->for($this->user, 'author')->create();

        $response = $this->actingAs($this->user)->postJson($this->articlePath($article) . '/favorite');

        $response->assertOk();
        $this->assertDatabaseCount('favorites', 1);
    }

    /** @test */
    public function favorite_2回お気に入りしても変化しないこと()
    {
        $article = Article::factory()->for($this->user, 'author')->create();

        $this->actingAs($this->user)->postJson($this->articlePath($article) . '/favorite');
        $response = $this->actingAs($this->user)->postJson($this->articlePath($article) . '/favorite');

        $response->assertOk();
        $this->assertDatabaseCount('favorites', 1);
    }

    /** @test */
    public function unfavorite_記事のお気に入りを解除できること()
    {
        $article = Article::factory()->for($this->user, 'author')->create();
        Favorite::factory()->for($this->user)->for($article)->create();

        $response = $this->actingAs($this->user)->deleteJson($this->articlePath($article) . '/favorite');

        $response->assertOk();
        $this->assertDatabaseCount('favorites', 0);
    }

    private function articlePath(Article $article): string
    {
        return $this->basePath . "/{$article->slug}";
    }
}
