<?php

namespace App\UseCases;

use App\Models\Article;
use App\Models\User;

final class UpdateArticle
{
    /**
     * 記事を更新する
     */
    public function run(Article $article, array $articleData): Article
    {
        $article->title = $articleData['title'];
        $article->description = $articleData['description'];
        $article->body = $articleData['body'];
        $article->save();

        return $article;
    }
}
