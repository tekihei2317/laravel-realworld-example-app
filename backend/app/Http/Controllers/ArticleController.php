<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\UseCases\StoreArticle;
use App\UseCases\UpdateArticle;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\DeleteArticleRequest;
use App\Http\Requests\UpdateArticleRequest;

class ArticleController extends Controller
{
    public function __construct(
        private Article $articleModel,
    ) {
    }

    /**
     * 記事を検索する
     */
    public function index(): ArticleCollection
    {
        return ArticleCollection::make($this->articleModel->filterByConditions()->get());
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
     * 記事を更新する
     */
    public function update(Article $article, UpdateArticleRequest $request, UpdateArticle $updateArticle)
    {
        $updatedArticle = $updateArticle->run($article, $request->validated()['article']);

        return ArticleResource::make($updatedArticle);
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
