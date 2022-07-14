<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/users', [AuthController::class, 'register']);
Route::post('/users/login', [AuthController::class, 'login']);

Route::resource('profiles', ProfileController::class)->only(['show']);
Route::middleware('auth')->group(function () {
    Route::get('/user', [AuthController::class, 'show']);
    Route::put('/user', [AuthController::class, 'update']);

    Route::get('articles/feed', [ArticleController::class, 'getFeed']);
    Route::resource('articles', ArticleController::class)->only(['store', 'update', 'destroy']);
    Route::resource('articles.comments', CommentController::class)->only(['store', 'destroy']);

    Route::prefix('articles')->group(function () {
        Route::post('/{article}/favorite', [ArticleController::class, 'favorite']);
        Route::delete('/{article}/favorite', [ArticleController::class, 'unfavorite']);
    });

    Route::prefix('profiles')->group(function () {
        Route::post('{profile}/follow', [ProfileController::class, 'follow']);
        Route::delete('{profile}/follow', [ProfileController::class, 'unfollow']);
    });
});

Route::resource('articles', ArticleController::class)->only(['index', 'show']);
Route::resource('articles.comments', CommentController::class)->only(['index']);

Route::resource('tags', TagController::class)->only(['index']);
