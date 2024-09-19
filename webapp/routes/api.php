<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserLikeController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\DealController;

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

Route::post('users_page', [UserController::class, 'index']); // เส้นทาง GET สำหรับดึงข้อมูลผู้ใช้ทั้งหมด
Route::post('users', [UserController::class, 'store']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'destroy']);


Route::post('products_page', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::put('products/{id}', [ProductController::class, 'update']);
Route::delete('products/{id}', [ProductController::class, 'destroy']);


Route::post('categories_page', [CategoryController::class, 'index']);
Route::post('categories', [CategoryController::class, 'store']);
Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::put('categories/{id}', [CategoryController::class, 'update']);
Route::delete('categories/{id}', [CategoryController::class, 'destroy']);


Route::post('/likes', [UserLikeController::class, 'store']);
Route::delete('/likes/{id}', [UserLikeController::class, 'destroy']);
Route::get('/likes', [UserLikeController::class, 'index']);
Route::get('/likes/{id}', [UserLikeController::class, 'show']);
Route::put('/likes/{id}', [UserLikeController::class, 'update']);


Route::post('preorders', [PreorderController::class, 'store']);
Route::get('preorders/{id}', [PreorderController::class, 'show']);
Route::put('preorders/{id}', [PreorderController::class, 'update']);
Route::delete('preorders/{id}', [PreorderController::class, 'destroy']);
Route::get('preorders', [PreorderController::class, 'index']);


Route::post('deals', [DealController::class, 'store']);
Route::get('deals/{id}', [DealController::class, 'show']);
Route::put('deals/{id}', [DealController::class, 'update']);
Route::delete('deals/{id}', [DealController::class, 'destroy']);
Route::get('deals', [DealController::class, 'index']);