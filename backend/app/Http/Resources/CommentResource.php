<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Comment */
        $comment = $this->resource;
        assert($comment->relationLoaded('author'), true);

        return [
            'id' => $comment->id,
            'body' => $comment->body,
            'createdAt' => $comment->created_at,
            'updatedAt' => $comment->updated_at,
            // TODO: ユーザーが削除された場合の処理
            'author' => ProfileResource::make($comment->author),
        ];
    }
}
