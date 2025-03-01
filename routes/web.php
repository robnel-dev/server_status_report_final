<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController, DiskController, 
                         AntivirusController, LogController,
                         PhysicalCheckController};
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::middleware(['auth'])->group(function () {
    // Default Breeze Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Sections
    Route::get('/disks', [DiskController::class, 'index'])->name('disks');
    Route::get('/antivirus', [AntivirusController::class, 'index'])->name('antivirus');
    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    Route::get('/physical-checks', [PhysicalCheckController::class, 'index'])->name('physical-checks');
    Route::post('/physical-checks', [PhysicalCheckController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
