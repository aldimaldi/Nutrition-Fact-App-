<?php

use App\Http\Controllers\BodyProfileController;
use App\Http\Controllers\FoodScanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();

        return view('dashboard', [
            'character' => $user->character,
            'target' => $user->nutritionTarget,
            'todayLogs' => $user->foodLogs()->whereDate('eaten_at', today())->latest('eaten_at')->get(),
        ]);
    })->name('dashboard');

    // Onboarding / update profil tubuh -> otomatis hitung ulang TDEE & target nutrisi
    Route::post('/body-profile', [BodyProfileController::class, 'store'])->name('body-profile.store');

    // Scan makanan (queue) + polling status + riwayat
    Route::post('/food-logs/scan', [FoodScanController::class, 'store'])->name('food-logs.scan');
    Route::get('/food-logs/{foodLog}', [FoodScanController::class, 'show'])->name('food-logs.show');
    Route::get('/food-logs', [FoodScanController::class, 'index'])->name('food-logs.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
