<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\PostsController;
use App\Http\Controllers\API\FollowController;
use App\Http\Controllers\API\UserController;

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


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
#Route::resource('posts', PostsController::class)->exclude(['index', 'show']);

Route::middleware('auth:api')->group(function() {
    Route::resource('products', ProductController::class);

    Route::resource('posts', PostsController::class);
    Route::post('likes', [PostsController::class, 'likes']);
    Route::post('unlikes', [PostsController::class, 'unlikes']);
    Route::get('listlikes/{id}', [PostsController::class, 'listlikes']);
    Route::post('comments', [PostsController::class, 'comments']);
    Route::get('listcomments/{id}', [PostsController::class, 'listcomments']);

    Route::get('followers/{id?}', [FollowController::class, 'followers']);
    Route::get('following/{id?}', [FollowController::class, 'following']);

    Route::resource('user', UserController::class);
    Route::post('search', [UserController::class, 'search']);
    Route::post('follows', [UserController::class, 'follows']);
    Route::post('unfollow', [UserController::class, 'unfollow']);
});
