<?php

namespace App\UseCases;

use App\Models\User;

final class FollowUser
{
    /**
     * ユーザーをフォローする
     */
    public function run(User $currentUser, User $userToFollow): User
    {
        $currentUser->followees()->syncWithoutDetaching([$userToFollow->id]);

        return $userToFollow->fresh();
    }
}
