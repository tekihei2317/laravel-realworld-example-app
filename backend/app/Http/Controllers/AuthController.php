<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
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
        $user = $this->userModel->create($request->makeUser());

        /** @var string */
        $token = auth()->login($user);

        return response()->json($this->userResource($user, $token), 201);
    }

    /**
     * ログインする
     */
    public function login(LoginRequest $request)
    {
        $token = auth()->attempt($request->validated()['user']);

        if ($token) {
            return $this->userResource(auth()->user(), $token);
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * ログイン中のユーザーを取得する
     */
    public function show()
    {
        // TODO: JWTのトークンを取得する
        $token = '';

        return $this->userResource(auth()->user(), $token);
    }

    /**
     * ログイン中のユーザーの情報を更新する
     */
    public function update(UpdateUserRequest $request)
    {
        $currentUser = auth()->user();
        $currentUser->update($request->validated()['user']);

        return $this->userResource($currentUser, '');
    }

    /**
     * ユーザーをレスポンス用に整形する
     */
    private function userResource(User $user, string $token): array
    {
        return [
            'user' => [
                'username' => $user->username,
                'email' => $user->email,
                'bio' => $user->bio,
                'image' => $user->image,
                'token' => $token,
            ]
        ];
    }
}
