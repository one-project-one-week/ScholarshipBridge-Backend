<?php

use App\Http\Middleware\GuestUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ScholarshipController;
use Database\Factories\Saved_ScholarshipFactory;
use App\Http\Controllers\SaveScholarshipController;



Route::prefix('v1/')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->middleware(GuestUser::class);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile/{user}', [AuthController::class, 'profile']);
        Route::delete('logout', [AuthController::class, 'logout']);
        Route::post('passwordchange/{user}', [AuthController::class, 'passwordChange']);
        Route::delete('/delete/{user}', [AuthController::class, 'delete']);
    });



    Route::prefix('/comment')->middleware('auth:sanctum')->group(function () {
        Route::post('/', [CommentController::class, 'create']);
        Route::delete('/{comment}', [CommentController::class, 'delete']);
    });

    Route::get('/showAll', [ScholarshipController::class, 'showAll']);
    Route::get('/showOne/{scholarship}', [ScholarshipController::class, 'showOne']);


    Route::prefix('/scholarship')->middleware('auth:sanctum')->group(function () {
        Route::post('/', [ScholarshipController::class, 'create'])->middleware('admin');
        Route::get('/search', [ScholarshipController::class, 'search']);
        Route::put('/update/{id}', [ScholarshipController::class, 'update'])->middleware('admin');
        Route::delete('/delete/{scholarship}', [ScholarshipController::class, 'delete'])->middleware('admin');


        Route::post('/save/{scholarship}/{user}', [SaveScholarshipController::class, 'save']);
        Route::get('save/{user}', [SaveScholarshipController::class, 'show']);
        Route::delete('save/{scholarship}/{user}', [SaveScholarshipController::class, 'delete']);
    });



    // ai chat message
    Route::prefix('ai/')->group(function () {
        Route::post('question/{id}', [ChatController::class, 'question'])->middleware('auth:sanctum');
        Route::get('/history/{id}', [ChatController::class, 'mockhistory'])->middleware('auth:sanctum');
        Route::post('/general/{id}', [ChatController::class, 'generalChat'])->middleware('auth:sanctum');
        Route::get('/general/history/{id}', [ChatController::class, 'generalHistory'])->middleware('auth:sanctum');
    });


    Route::prefix('/admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/user', [AdminController::class, 'userList']);
        Route::delete('/user/delete/{id}', [AdminController::class, 'userDelete']);
    });
});
