<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CommentResource;

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
    public function store()
    {
    }

    /**
     * コメントを削除する
     */
    public function destroy(): void
    {
    }
}
