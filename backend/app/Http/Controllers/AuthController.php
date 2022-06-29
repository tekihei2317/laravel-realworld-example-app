<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreUserRequest;
use Symfony\Component\HttpFoundation\Response;

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
     * ログインする
     */
    public function login(LoginRequest $request)
    {
        eval(\Psy\sh());
        $token = auth()->attempt($request->validated());


        if ($token) {
            return $this->userResource(auth()->user(), $token);
        }

        abort(Response::HTTP_FORBIDDEN);
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
