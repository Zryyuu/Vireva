<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AdminVillaController;
use App\Http\Controllers\Admin\AdminPetugasController;
use App\Http\Controllers\Admin\AdminTransaksiController;
use Illuminate\Support\Facades\Route;

// Public Route
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Midtrans Webhook (Disabled)
// Route::post('/midtrans-callback', [BookingController::class, 'callback'])->name('midtrans.callback');

// Auth Routes
Route::get('/dashboard', function () {
    if (Auth::user() && in_array(Auth::user()->role, ['admin', 'superadmin'])) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Booking Routes
    Route::get('/bookings/explore', [BookingController::class, 'explore'])->name('bookings.explore');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{villa}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{pemesanan}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{pemesanan}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{pemesanan}/upload-proof', [BookingController::class, 'uploadProof'])->name('bookings.upload-proof');
});

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('villa', AdminVillaController::class);
    Route::get('/transaksi', [AdminTransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/{id}/action', [AdminTransaksiController::class, 'processAction'])->name('transaksi.action');
    Route::get('/reservasi', [\App\Http\Controllers\Admin\AdminReservasiController::class, 'index'])->name('reservasi.index');
    Route::post('/reservasi/manual', [\App\Http\Controllers\Admin\AdminReservasiController::class, 'storeManual'])->name('reservasi.manual');
    Route::post('/reservasi/{id}/verify', [\App\Http\Controllers\Admin\AdminReservasiController::class, 'verifyPayment'])->name('reservasi.verify');

    // Super Admin Only
    Route::middleware([\App\Http\Middleware\SuperAdminMiddleware::class])->group(function () {
        Route::resource('petugas', AdminPetugasController::class);
        Route::resource('biaya', \App\Http\Controllers\Admin\BiayaController::class);
        Route::get('/laporan', [\App\Http\Controllers\Admin\AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/tamu', [\App\Http\Controllers\Admin\AdminTamuController::class, 'index'])->name('tamu.index');
    });
});

require __DIR__.'/auth.php';
