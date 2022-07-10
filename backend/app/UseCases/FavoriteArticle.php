<?php

namespace App\UseCases;

use App\Models\User;
use App\Models\Article;
use App\Models\Favorite;

final class FavoriteArticle
{
    /**
     * 記事をお気に入りする
     */
    public function run(User $user, Article $article): Article
    {
        $favorite = Favorite::query()->where('user_id', $user->id)
            ->where('article_id', $article->id)
            ->first();

        if ($favorite === null) {
            (new Favorite)->user()->associate($user)
                ->article()->associate($article)
                ->save();
        }

        return $article->fresh();
    }
}
