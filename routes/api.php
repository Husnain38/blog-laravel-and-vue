<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::apiResource("blogs", PostController::class);
Route::post("blogs/{postId}/comment", [CommentController::class, 'store']);
Route::post('blogs/update/{id}', [PostController::class, 'update']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);
Route::get('/user', function (Request $request){
    return $request->user();
})->middleware('auth:api');


