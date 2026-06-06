<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PromotionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Intelligent Home Redirect
    Route::get('/', function () {
        if (Auth::user()->role === 'Admin') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('pos.index');
    });

    // Admin-Only Area
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Modules
        Route::resource('inventory', InventoryController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserController::class);
        Route::resource('promotions', PromotionController::class);

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/gcash', [SettingController::class, 'updateGcashQr'])->name('settings.gcash.update');
        Route::post('/settings/profile', [SettingController::class, 'updateStoreProfile'])->name('settings.profile.update');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/stats', [ReportController::class, 'stats'])->name('reports.stats');
        Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    });

    // Shared POS Area (Both Admin and Cashier)
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
});
