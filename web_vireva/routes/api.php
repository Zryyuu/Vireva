<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VillaController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\AdminStatsController;
use App\Http\Controllers\Api\ApiAdminController;
use App\Http\Controllers\Api\PaymentCallbackController;

// Auth API
Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    
    // Booking API
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);

    // Admin API
    Route::get('/admin/stats', [AdminStatsController::class, 'index']);
    Route::get('/admin/bookings', [ApiAdminController::class, 'listBookings']);
    Route::post('/admin/villa', [ApiAdminController::class, 'storeVilla']);
    Route::post('/admin/villa/{id}', [ApiAdminController::class, 'updateVilla']);
    Route::delete('/admin/villa/{id}', [ApiAdminController::class, 'destroyVilla']);
    Route::post('/admin/transaksi/{id}/action', [ApiAdminController::class, 'processBookingAction']);
});

// Midtrans Callback (Public but handled by Midtrans)
Route::post('/midtrans/callback', [PaymentCallbackController::class, 'handle']);

// Public API
Route::get('/villas', [VillaController::class, 'index']);
Route::get('/villas/{villa}', [VillaController::class, 'show']);
