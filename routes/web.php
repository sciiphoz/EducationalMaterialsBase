<?php

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
    
    Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('materials.show');

    Route::post('/material/{id}/like', [LikeController::class, 'addLike'])->name('add.like');
    Route::post('/material/{id}/dislike', [LikeController::class, 'addDislike'])->name('add.dislike');
    Route::post('/materials/{material}/comment', [MaterialController::class, 'addComment'])->name('add.comment');


    Route::get('/material/add', [MaterialController::class, 'add'])->name('material.create');
    Route::get('/material/delete/{material}', [MaterialController::class, 'delete'])->name('material.delete');
    
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
});


Route::middleware('check.admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::resource('users', UserController::class)->except(['show']);
    
    Route::delete('/materials/{material}', [AdminController::class, 'destroyMaterial'])->name('materials.destroy');
    Route::put('/materials/{material}', [AdminController::class, 'updateMaterial'])->name('materials.update');
});
    
