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
        ];
    }
}
