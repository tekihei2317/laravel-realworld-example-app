<?php

namespace App\UseCases;

use App\Models\User;
use App\Models\Article;
use App\Models\Favorite;

final class UnfavoriteArticle
{
    /**
     * 記事のお気に入りを解除する
     */
    public function run(User $user, Article $article): Article
    {
        Favorite::query()->where('user_id', $user->id)
            ->where('article_id', $article->id)
            ->delete();

        return $article->fresh();
    }
}
