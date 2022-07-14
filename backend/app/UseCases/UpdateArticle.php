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
        if (isset($articleData['title'])) $article->title = $articleData['title'];
        if (isset($articleData['description'])) $article->description = $articleData['description'];
        if (isset($articleData['body'])) $article->body = $articleData['body'];
        $article->save();

        return $article;
    }
}
