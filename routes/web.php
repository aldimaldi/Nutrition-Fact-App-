<?php

use App\Http\Controllers\AwardController;
use App\Http\Controllers\BodyProfileController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\FoodScanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    // Rute dashboard sekarang memanggil fungsi dashboard() di dalam FoodScanController
    Route::get('/dashboard', [FoodScanController::class, 'dashboard'])->name('dashboard');

    // Onboarding / update profil tubuh -> otomatis hitung ulang TDEE & target nutrisi
    Route::post('/body-profile', [BodyProfileController::class, 'store'])->name('body-profile.store');

    // Scan makanan (queue) + polling status + riwayat
    Route::post('/food-logs/scan', [FoodScanController::class, 'store'])->name('food-logs.scan');
    Route::get('/food-logs/{foodLog}', [FoodScanController::class, 'show'])->name('food-logs.show');
    Route::get('/food-logs', [FoodScanController::class, 'index'])->name('food-logs.index');

    // charater route
    Route::get('/character', [CharacterController::class, 'index'])->name('character.index');
    Route::post('/character', [CharacterController::class, 'update'])->name('character.update');

    Route::get('/stats', [StatController::class, 'index'])->name('stats.index');

    Route::get('/awards', [AwardController::class, 'index'])->name('awards.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';