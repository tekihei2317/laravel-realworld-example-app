<?php

namespace App\UseCases;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Str;

final class StoreArticle
{
    public function __construct(private UpdateArticleTags $updateArticleTags)
    {
    }

    /**
     * 記事を登録する
     */
    public function run(User $author, array $articleData): Article
    {
        $articleData += ['slug' => Str::slug($articleData['title'])];

        $article =  $author->articles()->create($articleData);
        $this->updateArticleTags->run($article, $articleData['tagList'] ?? []);

        return $article;
    }
}
