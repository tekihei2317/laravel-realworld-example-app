<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'body', 'slug'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** 記事の執筆者 */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** 記事のコメント一覧 */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /** タグ一覧 */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag', 'article_id', 'tag_id');
    }

    /** お気に入りしたユーザー一覧 */
    public function favoriteUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'article_id', 'user_id');
    }

    /**
     * 条件でフィルタリングする
     */
    public function filterByConditions(array $conditions = []): Builder
    {
        $query = self::query()->when(
            isset($conditions['tag']),
            fn (Builder $query) => $this->filterByRelation($query, 'tags', 'name', $conditions['tag'])
        )->when(
            isset($conditions['author']),
            fn (Builder $query) => $this->filterByRelation($query, 'author', 'username', $conditions['author'])
        )->when(
            isset($conditions['favorited']),
            fn (Builder $query) => $this->filterByRelation($query, 'favoriteUsers', 'username', $conditions['favorited'])
        );

        return $query;
    }

    /**
     * フォローしているユーザーの記事一覧を取得する
     */
    public function getFeed(User $user): Builder
    {
        $followingUsers = $user->followees;
        $query = self::query()->whereIn('user_id', $followingUsers->pluck('id'));

        return $query;
    }

    private function filterByRelation(Builder $query, string $relation, string $column, $value): Builder
    {
        return $query->whereRelation($relation, $column, $value);
    }
}
