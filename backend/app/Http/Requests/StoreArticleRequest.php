<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'article.title' => 'required|string|max:255',
            'article.description' => 'required|string|max:255',
            'article.body' =>  'required|string|max:10000',
        ];
    }
}
