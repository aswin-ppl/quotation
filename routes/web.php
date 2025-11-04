<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\ProductController;

Route::middleware('auth')->group(function () {

    // dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User and Roles
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class)->middleware('auth');
    
    Route::view('/quotation','quotation.view')->name('quotation.view');

    // Products
    Route::resource('products', ProductController::class);
    Route::get('products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
