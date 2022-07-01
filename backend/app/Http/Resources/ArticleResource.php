<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        $article = $this->resource;
        $author = $article->author;

        return [
            'slug' => $article->slug,
            'title' => $article->title,
            'description' => $article->description,
            'body' => $article->body,
            'author' => [
                'username' => $author->username,
                'bio' => $author->bio,
                'image' => $author->image,
                'following' => 'TODO:',
            ],
        ];
    }
}
