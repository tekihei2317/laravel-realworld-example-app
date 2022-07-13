<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Follow extends Model
{
    use HasFactory;

    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function followee(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
