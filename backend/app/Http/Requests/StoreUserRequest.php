<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user.username' => 'required|string|max:255|unique:users,username',
            'user.email' => 'required|email|max:255|unique:users,email',
            'user.password' => 'required|string|max:255',
        ];
    }

    public function makeUser(): array
    {
        $user = $this->validated()['user'];

        return array_merge($user, ['password' => Hash::make($user['password'])]);
    }
}
