<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    DiskController,
    AntivirusController,
    ServerAntivirusController,
    LogController,
    PhysicalCheckController,
    ServerDiskController
};
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::middleware(['auth'])->group(function () {
    // Default Breeze Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Sections
    Route::get('/disks', [ServerDiskController::class, 'index'])->name('disks');
    Route::get('/antivirus', [ServerAntivirusController::class, 'index'])->name('antivirus');
    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    // Physical Checks routes
    Route::get('/physical-checks', [PhysicalCheckController::class, 'index'])->name('physical-checks');
    Route::post('/physical-checks', [PhysicalCheckController::class, 'store']);
    Route::put('/physical-checks/{check}', [PhysicalCheckController::class, 'update'])->name('physical-checks.update');
    Route::delete('/physical-checks/{check}', [PhysicalCheckController::class, 'destroy'])->name('physical-checks.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
