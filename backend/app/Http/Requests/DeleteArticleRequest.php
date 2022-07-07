<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;

class DeleteArticleRequest extends FormRequest
{
    public function authorize()
    {
        $article = $this->getArticle();

        return $article->user_id === auth()->user()->id;
    }

    public function rules()
    {
        return [];
    }

    private function getArticle(): Article
    {
        return $this->article;
    }
}
