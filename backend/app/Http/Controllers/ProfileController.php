<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\UseCases\FollowUser;
use App\UseCases\UnfollowUser;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * ユーザー情報を取得する
     */
    public function show(User $profile): JsonResponse
    {
        return $this->profileResponse($profile);
    }

    /**
     * ユーザーをフォローする
     */
    public function follow(User $profile, FollowUser $followUser): JsonResponse
    {
        $followee = $followUser->run(auth()->user(), $profile);

        return $this->profileResponse($followee);
    }

    /**
     * ユーザーのフォローを解除する
     */
    public function unfollow(User $profile, UnfollowUser $unfollowUser): JsonResponse
    {
        $unfollowedUser = $unfollowUser->run(auth()->user(), $profile);

        return $this->profileResponse($unfollowedUser);
    }

    private function profileResponse(User $user): JsonResponse
    {
        return response()->json(['profile' => ProfileResource::make($user)]);
    }
}
