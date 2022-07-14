<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->getArticle()->user_id === auth()->user()->id;
    }

    public function rules(): array
    {
        return [
            'article.title' => 'sometimes|required|string|max:255',
            'article.description' => 'sometimes|required|string|max:255',
            'article.body' => 'sometimes|required|string|max:10000',
            'article.tagList' => 'sometimes|array',
        ];
    }

    public function getArticle(): Article
    {
        return $this->route('article');
    }
}
