<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        /** @var App\Models\User */
        $currentUserId = auth()->user()->id;

        return [
            'user.username' => ['sometimes', 'string', 'max:255', Rule::unique('users', 'username')->ignore($currentUserId)],
            'user.email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($currentUserId)],
            'user.image' => 'sometimes|url',
            'user.bio' => 'sometimes|string|max:2048'
        ];
    }
}
