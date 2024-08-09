<?php

use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/per-bulan', [AktivitasController::class, 'perBulan'])->name('aktivitas.perBulan');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
    
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');    
    Route::get('/aktivitas/create', [AktivitasController::class, 'create'])->name('aktivitas.create');    
    Route::post('/aktivitas/create', [AktivitasController::class, 'store']);    
    Route::post('/aktivitas/delete', [AktivitasController::class, 'destroy'])->name('aktivitas.delete');    
    Route::get('/aktivitas/{id}', [AktivitasController::class, 'edit'])->name('aktivitas.edit');    
    Route::post('/aktivitas/{id}', [AktivitasController::class, 'update']);    
});

require __DIR__.'/auth.php';
