<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteCommentRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CommentResource;
use App\Http\Requests\StoreCommentRequest;
use App\UseCases\AddComment;

class CommentController extends Controller
{
    /**
     * 記事のコメント一覧を取得する
     */
    public function index(Article $article): JsonResponse
    {
        $article->load('comments', 'comments.author');

        return response()->json(['comments' => CommentResource::collection($article->comments)]);
    }

    /**
     * 記事にコメントを追加する
     */
    public function store(Article $article, StoreCommentRequest $request, AddComment $addComment): CommentResource
    {
        $comment = $addComment->run($article, auth()->user(), $request->validated()['comment']);

        return CommentResource::make($comment);
    }

    /**
     * コメントを削除する
     */
    public function destroy(Article $article, Comment $comment, DeleteCommentRequest $request): void
    {
        // TODO: $articleを書かずにルートモデルバインディングできるようにする
        $comment->delete();
    }
}
