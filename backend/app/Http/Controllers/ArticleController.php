<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\StoreArticleRequest;
use App\UseCases\StoreArticle;

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
}
