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
Route::post('/register', [ApiAuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    
    // Booking Routes
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::post('/bookings/{id}/upload-bukti', [BookingController::class, 'uploadBukti']);

    // Admin Routes
    Route::middleware('can:admin-superadmin')->group(function () {
        Route::get('/admin/stats', [AdminStatsController::class, 'index']);
        
        // Villa Management
        Route::post('/admin/villas', [ApiAdminController::class, 'storeVilla']);
        Route::post('/admin/villas/{id}', [ApiAdminController::class, 'updateVilla']);
        Route::delete('/admin/villas/{id}', [ApiAdminController::class, 'destroyVilla']);
        
        // Booking Management
        Route::get('/admin/bookings', [ApiAdminController::class, 'listBookings']);
        Route::post('/admin/bookings/manual', [ApiAdminController::class, 'storeBookingManual']);
        Route::post('/admin/bookings/{id}/verify', [ApiAdminController::class, 'verifyPembayaran']);
        Route::post('/admin/transaksi/{id}/action', [ApiAdminController::class, 'processBookingAction']);
    });
});

// Midtrans Callback (Disabled)
// Route::post('/midtrans/callback', [PaymentCallbackController::class, 'handle']);

// Public API
Route::get('/villas', [VillaController::class, 'index']);
Route::get('/villas/{villa}', [VillaController::class, 'show']);
