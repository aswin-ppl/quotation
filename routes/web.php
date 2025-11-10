<?php

use App\Http\Controllers\QuotationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PincodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\ProductController;
use App\Http\Controllers\Master\CustomerController;

Route::middleware('auth')->group(function () {

    // dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User and Roles
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class)->middleware('auth');
    
    Route::get('/quotation/{id}/{address}/pdf', [QuotationController::class, 'generatePdf'])->name('quotation.pdf');
    Route::get('/quotation/{id}/preview', [QuotationController::class, 'preview'])->name('quotation.preview');
    // Route::get('/quotation/{id}/download', [QuotationController::class, 'download'])->name('quotation.download');
    Route::get('/quotation/{id}/download', [QuotationController::class, 'download'])->name('quotation.download');
    Route::resource('quotation', QuotationController::class);

    // Products
    Route::resource('products', ProductController::class);
    Route::get('products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');
    Route::get('/cart/products', [ProductController::class, 'getCartProducts'])->name('cart.products');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/{id}/addresses', [CustomerController::class, 'getAddresses']);

    //Search Locations
    Route::get('/districts/search', [LocationController::class, 'searchDistricts']);
    Route::get('/cities/search', [LocationController::class, 'searchCities']);
    Route::get('/pincodes/search', [LocationController::class, 'searchPincodes']);

    // Locations
    Route::get('/states', [LocationController::class, 'getStates']);
    Route::get('/districts/{state}', [LocationController::class, 'getDistricts']);
    Route::get('/cities/{district}', [LocationController::class, 'getCities']);
    Route::get('/pincodes/{city}', [LocationController::class, 'getPincodes']);
    Route::get('/pincode/search', [LocationController::class, 'searchPincode']);
    Route::get('/pincode/{pincode}', [LocationController::class, 'getPincodeDetails']);

    //Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/settings/create', [SettingController::class, 'create'])->name('settings.create');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Route::resource('customers', CustomerController::class);
    // Route::get('customers/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
