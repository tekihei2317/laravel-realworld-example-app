<?php

namespace App\UseCases;

use App\Models\Article;
use App\Models\Tag;

final class UpdateArticleTags
{
    public function run(Article $article, array $tagNames): Article
    {
        $tagIds = [];

        foreach ($tagNames as $tagName) {
            $tagIds[] = Tag::firstOrCreate(['name' => $tagName])->id;
        }
        $article->tags()->sync($tagIds);

        return $article;
    }
}
