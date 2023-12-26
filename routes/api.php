<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserContoller;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserContoller::class, 'register']);
Route::post('login', [UserContoller::class, 'login']);

// CRUD 
Route::get('recipe', [PostController::class, 'index']);
Route::post('recipe', [PostController::class, 'addPost']);
Route::get('recipe/{id}', [PostController::class, 'show']);
Route::put('recipe/{id}', [PostController::class, 'update']);
Route::delete('recipe/{id}', [PostController::class, 'destroy']);
