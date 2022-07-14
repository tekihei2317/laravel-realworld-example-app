<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Database\Factories\ArticleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function フォローしているユーザーの記事一覧を取得できること()
    {
        $followingUser1 = User::factory()->create();
        $followingUser2 = User::factory()->create();
        $notFollowingUser = User::factory()->create();

        $this->user->followees()->attach($followingUser1);
        $this->user->followees()->attach($followingUser2);

        $article1 = $followingUser1->articles()->save(Article::factory()->make());
        $article2 = $followingUser2->articles()->save(Article::factory()->make());
        $article3 = $followingUser2->articles()->save(Article::factory()->make());
        $notFollowingUser->articles()->save(Article::factory()->make());
        $notFollowingUser->articles()->save(Article::factory()->make());

        $expected = [$article1->id, $article2->id, $article3->id];
        $result = (new Article)->getFeed($this->user);

        $this->assertEquals($expected, $result->pluck('id')->toArray());
    }
}
