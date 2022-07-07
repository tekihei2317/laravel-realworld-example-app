<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users', [AuthController::class, 'register']);
Route::post('/users/login', [AuthController::class, 'login']);
Route::resource('articles', ArticleController::class)->only(['index', 'show']);

Route::middleware('auth')->group(function () {
    Route::get('/user', [AuthController::class, 'show']);
    Route::put('/user', [AuthController::class, 'update']);

    Route::resource('articles', ArticleController::class)->only(['store', 'update', 'destroy']);
});
