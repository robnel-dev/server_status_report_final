<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{
    ProfileController,
    DashboardController,
    DiskController,
    AntivirusController,
    LogController,
    PhysicalCheckController
};

// Public routes (Unauthenticated)
Route::get('/', fn () => Inertia::render('Auth/Login'))->name('login');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    // Sections
    Route::prefix('sections')->group(function () {
        Route::get('/disks', [DiskController::class, 'index'])->name('disks');
        Route::get('/antivirus', [AntivirusController::class, 'index'])->name('antivirus');
        Route::get('/logs', [LogController::class, 'index'])->name('logs');
    });

    // Physical Checks
    Route::prefix('physical-checks')->name('physical-checks.')->group(function () {
        Route::get('/', [PhysicalCheckController::class, 'index'])->name('index');
        Route::post('/', [PhysicalCheckController::class, 'store'])->name('store');
        Route::put('/{check}', [PhysicalCheckController::class, 'update'])->name('update');
        Route::delete('/{check}', [PhysicalCheckController::class, 'destroy'])->name('destroy');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

// Authentication routes (e.g., Laravel Breeze)
require __DIR__ . '/auth.php';
