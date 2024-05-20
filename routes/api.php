<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
// Route::get('auth/google', [GoogleAuthController::class, 'redirect']);
// Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);


Route::get('products', [ProductController::class, 'show']);
Route::post('products', [ProductController::class, 'store']);
Route::put('products/{id}', [ProductController::class, 'update']);
Route::delete('products/{id}', [ProductController::class, 'delete']);

Route::middleware(['web'])->group(function() {
    Route::get('auth/google', [GoogleAuthController::class, 'redirect']);
    Route::get('auth/google/callback', [GoogleAuthController::class, 'callbackGoogle']);
});

Route::middleware(['jwt-auth'])->group(function() {
    Route::get('categories', [CategoriesController::class, 'show']);
    Route::post('categories', [CategoriesController::class, 'store']);
    Route::put('categories/{id}', [CategoriesController::class, 'update']);
    Route::delete('categories/{id}', [CategoriesController::class, 'delete']);
});

// Route::get('product/{id}', [ProductController::class, 'id']);
// Route::get('/product/search/name={name}', [ProductController::class, 'name']);
// Route::put('product/{id}', [ProductController::class, 'update']);
// Route::delete('product/{id}', [ProductController::class,'delete']);
// Route::get('product/{id}/restore', [ProductController::class, 'restore']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });