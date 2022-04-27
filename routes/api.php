<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\PostController;
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

Route::post('login', [ApiAuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/like-unlike-post/{post}', [PostController::class, 'likeUnlikePost']);

    // Posts are accessible publicly, so it should be out of authentication
    Route::apiResource('posts', PostController::class)->except('index');

});

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
