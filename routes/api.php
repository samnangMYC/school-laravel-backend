<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DepartmentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Group under /auth prefix
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});


// Group under / prefix
Route::prefix('/')->group(function () {
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::get('/teachers', [TeacherController::class, 'index']);
});

// admin route
 Route::prefix('/admin')->group(function () {
     // Department Gateway
     Route::post('/department/create', [DepartmentController::class, 'store']);

     // Teacher Gateway
     Route::get('/teacher/{id}', [TeacherController::class, 'show']);
     Route::post('/teacher/create', [TeacherController::class, 'store']);
     Route::post('/teacher/edit/{id}', [TeacherController::class, 'update']);
     Route::delete('/teacher/{id}', [TeacherController::class, 'destroy']);

 });


