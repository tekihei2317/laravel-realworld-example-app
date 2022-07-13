<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    /**
     * 条件でフィルタリングする
     */
    public function filterByConditions(array $conditions = []): Builder
    {
        return $this->query()->limit(20);
    }
}
