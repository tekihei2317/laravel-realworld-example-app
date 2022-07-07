<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteArticleRequest;
use App\Models\Article;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\StoreArticleRequest;
use App\UseCases\StoreArticle;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function __construct(
        private Article $articleModel,
    ) {
    }

    /**
     * 記事を登録する
     */
    public function store(StoreArticleRequest $request, StoreArticle $storeArticle): ArticleResource
    {
        $article = $storeArticle->run(auth()->user(), $request->validated()['article']);

        return ArticleResource::make($article);
    }

    /**
     * 記事詳細を取得する
     */
    public function show(Article $article): JsonResponse
    {
        return response()->json(['article' => ArticleResource::make($article)]);
    }

    /**
     * 記事を削除する
     */
    public function destroy(Article $article, DeleteArticleRequest $request): JsonResponse
    {
        $article->delete();

        return response()->json();
    }
}
