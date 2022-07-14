<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Article */
        $article = $this->resource;

        assert($article->relationLoaded('author'));
        assert($article->relationLoaded('tags'));

        $author = $article->author;

        return [
            'slug' => $article->slug,
            'title' => $article->title,
            'description' => $article->description,
            'body' => $article->body,
            'tagList' => $article->tags->pluck('name')->toArray(),
            'createdAt' => $article->created_at,
            'updatedAt' => $article->updated_at,
            'author' => [
                'username' => $author->username,
                'bio' => $author->bio,
                'image' => $author->image,
                'following' => 'TODO:',
            ],
        ];
    }
}
