<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlindController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderBlindController;
use App\Http\Controllers\Api\OrderLineController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::get('cities', [CityController::class, 'index']); // Public - cities dropdown

// Protected routes - require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::get('auth/user', [AuthController::class, 'user']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/logout-all', [AuthController::class, 'logoutAll']);

    // Customers
    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/{identifier}', [CustomerController::class, 'show']);
    Route::put('customers/{identifier}', [CustomerController::class, 'update']);

    // Blinds CRUD
    Route::apiResource('blinds', BlindController::class);
    Route::get('blinds/stock/summary', [BlindController::class, 'stockSummary']);
    Route::get('blinds/colors', [BlindController::class, 'getColors']);

// Orders CRUD
Route::apiResource('orders', OrderController::class);
    Route::post('orders/check-customer', [OrderController::class, 'checkCustomer']);
    Route::get('reports', [OrderController::class, 'reports']);

// Order lines nested operations
Route::get('orders/{order}/lines', [OrderLineController::class, 'index']);
Route::post('orders/{order}/lines', [OrderLineController::class, 'store']);
Route::put('orders/{order}/lines/reorder', [OrderLineController::class, 'reorder']);
Route::get('orders/{order}/lines/{line}', [OrderLineController::class, 'show']);
Route::put('orders/{order}/lines/{line}', [OrderLineController::class, 'update']);
Route::delete('orders/{order}/lines/{line}', [OrderLineController::class, 'destroy']);
Route::post('orders/{order}/lines/{line}/image', [OrderLineController::class, 'uploadImage']);

    // Order blinds nested operations
    Route::get('orders/{order}/blinds', [OrderBlindController::class, 'index']);
    Route::post('orders/{order}/blinds', [OrderBlindController::class, 'store']);
    Route::get('orders/{order}/blinds/{blind}', [OrderBlindController::class, 'show']);
    Route::put('orders/{order}/blinds/{blind}', [OrderBlindController::class, 'update']);
    Route::delete('orders/{order}/blinds/{blind}', [OrderBlindController::class, 'destroy']);
    Route::post('orders/{order}/blinds/{blind}/image', [OrderBlindController::class, 'uploadImage']);
});
