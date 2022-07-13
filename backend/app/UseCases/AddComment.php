<?php

namespace App\UseCases;

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;

final class AddComment
{
    /**
     * 記事にコメントを追加する
     */
    public function run(Article $article, User $author, array $commentData): Comment
    {
        /** @var Comment $comment */
        $comment = Comment::make(['body' => $commentData['body']]);
        $comment->article()
            ->associate($article)
            ->author()
            ->associate($author)
            ->save();

        return $comment;
    }
}
