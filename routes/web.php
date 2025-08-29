<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;


Route::get('/', [MaterialController::class, 'showMainPage'])->name('view.main');

Route::middleware('check.guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('view.register');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('view.login');

    Route::post('/register/base', [AuthController::class, 'register'])->name('register');     
    Route::post('/login/base', [AuthController::class, 'login'])->name('login');;
});

Route::middleware('check.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::resource('materials', MaterialController::class)->except(['index', 'show']);
    
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('materials.show');

    Route::get('/material/add', [MaterialController::class, 'add'])->name('material.create');
    Route::get('/material/delete/{material}', [MaterialController::class, 'delete'])->name('material.delete');
    
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
});


Route::middleware('check.admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::resource('users', UserController::class)->except(['show']);
    
    Route::get('/materials', [AdminController::class, 'materials'])->name('materials.index');
    Route::delete('/materials/{material}', [AdminController::class, 'destroyMaterial'])->name('materials.destroy');
    Route::put('/materials/{material}', [AdminController::class, 'updateMaterial'])->name('materials.update');
});
