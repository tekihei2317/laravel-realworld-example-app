<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    const RELATIONS = ['author', 'tags', 'favoriteUsers'];

    public function toArray($request)
    {
        /** @var Article */
        $article = $this->resource;

        assert($article->relationLoaded('author'));
        assert($article->relationLoaded('tags'));
        assert($article->relationLoaded('favoriteUsers'));

        $author = $article->author;

        return [
            'slug' => $article->slug,
            'title' => $article->title,
            'description' => $article->description,
            'body' => $article->body,
            'tagList' => $article->tags->pluck('name')->toArray(),
            'createdAt' => $article->created_at,
            'updatedAt' => $article->updated_at,
            'favorited' => $this->isFavoritedBy($article, auth()->user()), // FIXME: 最大で合計いいね数の数だけループが回るので、SQLでやったほうがよさそう
            'favoritesCount' => $article->favoriteUsers->count(),
            'author' => [
                'username' => $author->username,
                'bio' => $author->bio,
                'image' => $author->image,
                'following' => 'TODO:',
            ],
        ];
    }

    /**
     * お気に入りされているかどうか判定する
     */
    private function isFavoritedBy(Article $article, ?User $user): bool
    {
        if ($user === null) return false;

        return $article->favoriteUsers->contains(fn (User $favoriteUser) => $favoriteUser->id === $user->id);
    }
}
