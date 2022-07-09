<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->getComment()->user_id === auth()->user()->id;
    }

    public function rules()
    {
        return [];
    }

    public function getComment(): Comment
    {
        return $this->route('comment');
    }
}
