<?php

namespace App\UseCases;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Str;

final class StoreArticle
{
    /**
     * 記事を登録する
     */
    public function run(User $author, array $article): Article
    {
        $article = $article + ['slug' => Str::slug($article['title'])];

        return $author->articles()->create($article);
    }
}
