<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController ; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

});

Route::middleware('auth:sanctum')->group(function () {

 
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::delete('/user', [AuthController::class, 'destroy']);
    Route::get('/users', [AuthController::class, 'index']);          // View all users
    Route::delete('/users/{id}', [AuthController::class, 'destroyUser']); // Delete any user
});


Route::post('/login', [AuthController::class, 'login']);
