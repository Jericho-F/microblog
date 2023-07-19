<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::controller(PostController::class)->group(function() {
        Route::get('/post', 'userAuthPost');
        Route::post('/post', 'store');
        Route::post('/update/{id}', 'update');
        Route::get('/delete/{id}', 'destroy');
        Route::get('/like/{id}', 'like');
        Route::get('/delete/{id}', 'destroy');
        Route::post('/share/{id}', 'share');
    });

    Route::controller(UserController::class)->group(function() {
        Route::get('/user', 'index');
        Route::get('/follow/{id}', 'follow');
        Route::post('/logout', 'logout');
        Route::get('/search', 'search');
        Route::post('/update', 'updateProfile');
        Route::post('/change-image', 'changeImage');
        Route::get('followings/{id}', 'getFollowing');
        Route::get('followers/{id}', 'getFollower');
        Route::post('change-password', 'changePassword');
    });

    Route::controller(CommentController::class)->group(function() {
        Route::get('/comment/{id}/delete', 'destroy')->name('destroyComment');
        Route::post('/post/{id}/comment', 'store')->name('comment');
        Route::post('/comment/{id}/update', 'update')->name('commentUpdate');
    });
});
