<?php

namespace App\UseCases;

use App\Models\User;

final class UnfollowUser
{
    /**
     * ユーザーのフォローを解除する
     */
    public function run(User $currentUser, User $userToUnfollow): User
    {
        $currentUser->followees()->detach([$userToUnfollow->id]);

        return $userToUnfollow;
    }
}
