<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v2025\OrderController;
use App\Http\Controllers\API\v2025\ProductController;
use App\Http\Controllers\API\v2025\ShipperController;
use App\Http\Controllers\API\v2025\CategoryController;
use App\Http\Controllers\API\v2025\CustomerController;
use App\Http\Controllers\API\v2025\EmployeeController;
use App\Http\Controllers\API\v2025\SupplierController;
use App\Http\Controllers\API\v2025\OrderDetailController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('shippers', ShipperController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('order-details', OrderDetailController::class);
    Route::apiResource('suppliers', SupplierController::class);
});
