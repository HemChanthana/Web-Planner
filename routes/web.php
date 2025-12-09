<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/users', [AdminController::class, 'viewAllUser'])->name('admin.users');

    Route::get('admin/users/tasks',[AdminController::class, 'ViewAllTask'] )->name('admin.users.task');

    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');

    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
});



Route::post('/tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])
     ->name('tasks.toggle');



    


Route::middleware(['auth'])->group(function () {

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::get('/tasks/search', [TaskController::class, 'search'])->name('tasks.search');

//    Route::post('/tasks', [TaskController::class, 'show'])->name('tasks.store');

});




require __DIR__.'/auth.php';
