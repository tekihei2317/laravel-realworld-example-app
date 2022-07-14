<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User */
        $user = $this->resource;

        return [
            'username' => $user->username,
            'bio' => $user->bio,
            'image' => $user->image,
            'following' => $this->isFollowing(auth()->user(), $user),
        ];
    }

    /**
     * フォローしているかどうかを判定する
     */
    private function isFollowing(?User $currentUser, User $profile): bool
    {
        if ($currentUser === null) return false;

        return $currentUser->followees->contains(fn (User $followingUser) => $followingUser->id === $profile->id);
    }
}
