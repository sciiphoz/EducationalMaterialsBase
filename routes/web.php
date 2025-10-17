<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Route::get('/', [MaterialController::class, 'showMainPage'])->name('view.mainpage');

Route::middleware('check.guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('view.register');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('view.login');

    Route::post('/register/base', [AuthController::class, 'register'])->name('register');     
    Route::post('/login/base', [AuthController::class, 'login'])->name('login');;
});

Route::middleware('check.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/materials/{id}', [MaterialController::class, 'show'])->name('material.show');

    Route::post('/material/{id}/like', [LikeController::class, 'addLike'])->name('add.like');
    Route::post('/material/{id}/dislike', [LikeController::class, 'addDislike'])->name('add.dislike');
    Route::post('/material/{id}/comment', [CommentController::class, 'addComment'])->name('add.comment');

    Route::get('/material/create', [MaterialController::class, 'create'])->name('material.create');
    Route::post('/material/store', [MaterialController::class, 'store'])->name('material.store');

    Route::get('/material/{id}/edit', [MaterialController::class, 'edit'])->name('material.edit');
    Route::put('/material/{id}', [MaterialController::class, 'update'])->name('material.update');
    Route::delete('/material/{id}', [MaterialController::class, 'destroy'])->name('material.destroy');
    
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
});


Route::middleware('check.admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::resource('users', UserController::class)->except(['show']);
    
    Route::delete('/materials/{material}', [AdminController::class, 'destroyMaterial'])->name('materials.destroy');
    Route::put('/materials/{material}', [AdminController::class, 'updateMaterial'])->name('materials.update');
});
    
