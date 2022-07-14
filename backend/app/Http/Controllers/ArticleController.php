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
use App\UseCases\FavoriteArticle;
use App\UseCases\UnfavoriteArticle;

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
        return ArticleCollection::make($this->articleModel->filterByConditions()->orderByDesc('created_at')->get());
    }

    /**
     * フォローしているユーザーの記事一覧を取得する
     */
    public function getFeed(): ArticleCollection
    {
        return ArticleCollection::make($this->articleModel->getFeed(auth()->user())->get());
    }

    /**
     * 記事を登録する
     */
    public function store(StoreArticleRequest $request, StoreArticle $storeArticle): JsonResponse
    {
        $article = $storeArticle->run(auth()->user(), $request->validated()['article']);

        return $this->articleResponse($article, 201);
    }

    /**
     * 記事詳細を取得する
     */
    public function show(Article $article): JsonResponse
    {
        return $this->articleResponse($article);
    }

    /**
     * 記事を更新する
     */
    public function update(Article $article, UpdateArticleRequest $request, UpdateArticle $updateArticle): JsonResponse
    {
        $updatedArticle = $updateArticle->run($article, $request->validated()['article']);

        return $this->articleResponse($article);
    }

    /**
     * 記事を削除する
     */
    public function destroy(Article $article, DeleteArticleRequest $request): JsonResponse
    {
        $article->delete();

        return response()->json();
    }

    /**
     * 記事をお気に入りする
     */
    public function favorite(Article $article, FavoriteArticle $favoriteArticle): JsonResponse
    {
        $article = $favoriteArticle->run(auth()->user(), $article);

        return $this->articleResponse($article);
    }

    /**
     * 記事のお気に入りを解除する
     */
    public function unfavorite(Article $article, UnfavoriteArticle $unfavoriteArticle): JsonResponse
    {
        $article = $unfavoriteArticle->run(auth()->user(), $article);

        return $this->articleResponse($article);
    }

    private function articleResponse(Article $article, int $status = 200): JsonResponse
    {
        return response()->json(['article' => ArticleResource::make($article)], $status);
    }
}
