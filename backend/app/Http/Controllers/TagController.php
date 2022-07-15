<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        $tagNames = Tag::query()->pluck('name')->toArray();

        return response()->json(['tags' => $tagNames]);
    }
}
