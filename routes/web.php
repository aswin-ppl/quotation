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
use App\Http\Controllers\TempUploadController;

Route::middleware('auth')->group(function () {

    // dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // AJAX partial for products on dashboard (used by live search)
    Route::get('/dashboard/products', [DashboardController::class, 'productsPartial'])->name('dashboard.products');

    // User and Roles
    // Data endpoint for DataTables AJAX loading (place before resource to avoid wildcard capture)
    Route::get('users/data', [UserController::class, 'data'])->name('users.data');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class)->middleware('auth');

    Route::get('/quotations/{id}/preview/{address?}', [QuotationController::class, 'preview'])->name('quotation.preview');
    Route::get('/quotations/{id}/pdf/{address?}', [QuotationController::class, 'generatePdf'])->name('quotation.pdf');
    Route::get('/quotations/{id}/download/{address?}', [QuotationController::class, 'download'])->name('quotation.download');
    Route::resource('quotations', QuotationController::class);

    // Products
    // Special routes that must come before the resource to avoid being captured by products/{product}
    // Data endpoint for DataTables AJAX loading
    Route::get('products/data', [ProductController::class, 'data'])->name('products.data');
    Route::get('products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');
    Route::get('/cart/products', [ProductController::class, 'getCartProducts'])->name('cart.products');
    Route::resource('products', ProductController::class);

    // Customers
    // Special customer routes (place before resource to avoid wildcard capture)
    Route::get('customers/data', [CustomerController::class, 'data'])->name('customers.data');
    Route::get('customers/restore/{id}', [CustomerController::class, 'restore'])->name('customers.restore');
    Route::get('/customers/{id}/addresses', [CustomerController::class, 'getAddresses']);
    Route::resource('customers', CustomerController::class);

    //Search Locations
    Route::get('/districts/search', [LocationController::class, 'searchDistricts']);
    Route::get('/cities/search', [LocationController::class, 'searchCities']);
    Route::get('/pincodes/search', [LocationController::class, 'searchPincodes']);

    // Locations
    Route::get('/states', [LocationController::class, 'getStates']);
    Route::get('/districts/{state}', [LocationController::class, 'getDistricts']);
    Route::get('/cities/{district}', [LocationController::class, 'getCities']);
    Route::get('/pincodes/district/{district}', [LocationController::class, 'getPincodesByDitrict']);
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

    // Temporary upload endpoints for wizard (AJAX)
    Route::post('/uploads/temp-image', [TempUploadController::class, 'upload'])->name('temp.upload');
    Route::delete('/uploads/temp-image', [TempUploadController::class, 'delete'])->name('temp.delete');

    Route::get('/private-image/{path}', function ($path) {
        $fullPath = storage_path('app/private/' . $path);

        if (!file_exists($fullPath)) {
            abort(404, 'Image not found: ' . $fullPath);
        }

        return response()->file($fullPath);
    })
        ->where('path', '.*');


});

require __DIR__ . '/auth.php';
