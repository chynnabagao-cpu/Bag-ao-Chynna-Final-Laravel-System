<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\DashboardController;

Route::middleware('auth:sanctum')->group(function () {
    // Products
    Route::apiResource('products', ProductController::class);

    // Sales
    Route::post('sales', [SaleController::class, 'store']);
    Route::get('sales', [SaleController::class, 'index']);

    // Dashboard Stats
    Route::get('dashboard/stats', [DashboardController::class, 'index']);
});
