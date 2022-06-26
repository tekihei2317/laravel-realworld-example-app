<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreUserRequest;

class AuthController extends Controller
{
    public function __construct(
        private User $userModel
    ) {
    }

    /**
     * ユーザーを登録する
     */
    public function register(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userModel->create($request->validated());

        /** @var string */
        $token = auth()->login($user);

        return response()->json($this->userResource($user, $token), 201);
    }

    /**
     * ユーザーをレスポンス用に整形する
     */
    private function userResource(User $user, string $token): array
    {
        return [
            'username' => $user->username,
            'email' => $user->email,
            'bio' => $user->bio,
            'image' => $user->image,
            'token' => $token,
        ];
    }
}
