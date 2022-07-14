<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterArticleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function 記事をタグで検索できること()
    {
        $tag1 = Tag::factory()->create(['name' => 'Laravel']);
        $tag2 = Tag::factory()->create(['name' => 'PHP']);

        $article1 = Article::factory()->for($this->user, 'author')->create();
        $article2 = Article::factory()->for($this->user, 'author')->create();
        $article3 = Article::factory()->for($this->user, 'author')->create();

        $article1->tags()->attach($tag1);
        $article2->tags()->attach($tag1);
        $article3->tags()->attach($tag2);

        $result = (new Article)->filterByConditions(['tag' => $tag1->name]);
        $expected = [$article1->id, $article2->id];

        $this->assertEquals($expected, $result->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function 記事を執筆者で検索できること()
    {
        $author1 = User::factory()->create();
        $author2 = User::factory()->create();

        $article1 = Article::factory()->for($author1, 'author')->create();
        $article2 = Article::factory()->for($author1, 'author')->create();
        Article::factory()->for($author2, 'author')->create();

        $result = (new Article)->filterByConditions(['author' => $author1->username]);
        $expected = [$article1->id, $article2->id];

        $this->assertEquals($expected, $result->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function お気に入りした記事を取得できること()
    {
        $article1 = Article::factory()->for($this->user, 'author')->create();
        $article2 = Article::factory()->for($this->user, 'author')->create();
        Article::factory()->for($this->user, 'author')->create();

        $article1->favoriteUsers()->attach($this->user);
        $article2->favoriteUsers()->attach($this->user);

        $result = (new Article)->filterByConditions(['favorited' => $this->user->username]);
        $expected = [$article1->id, $article2->id];

        $this->assertEquals($expected, $result->pluck('id')->toArray());
    }
}
