<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;

class DeleteArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $article = $this->getArticle();

        return $article->user_id === auth()->user()->id;
    }

    public function rules(): array
    {
        return [];
    }

    private function getArticle(): Article
    {
        return $this->article;
    }
}
