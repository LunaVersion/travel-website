<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

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

// Route::get('/test', function () {
//     return "Luna";
// });

Route::controller(AuthController::class)
    ->prefix('v1/')
    ->group(function () {
    Route::post('/login', 'login');
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'resetPassword'); 
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function() {
        Route::get('logout', 'logout'); 
    });

    Route::controller(PostController::class) -> group(function () {
        Route::get('/posts','index'); // lấy ds bài viết
    
        Route::get('/posts/{id}', 'show'); // lấy chi tiết
        
        Route::post('/posts', 'store'); // thêm bài chính
        
        Route::post('/posts/draft', 'storeDraft'); //thêm nháp mới
        
        Route::put('/posts/{id}', 'update'); //sửa bài 
            
        Route::put('/posts/{id}/draft', 'updatePostToDraft'); //thêm nháp của bài chính chưa có nháp
    
        Route::delete('/posts/{id}', 'destroy'); // xóa 
    });
});
