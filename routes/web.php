<?php

use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->middleware('auth')->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
});

// Warehouse management (admin & worker only)
Route::middleware(['auth', 'role:admin,worker'])->group(function () {
    Route::get('/warehouse', [\App\Http\Controllers\WarehousePartController::class, 'index'])->name('warehouse.index');
    Route::get('/warehouse/create', [\App\Http\Controllers\WarehousePartController::class, 'create'])->name('warehouse.create');
    Route::post('/warehouse', [\App\Http\Controllers\WarehousePartController::class, 'store'])->name('warehouse.store');
    Route::get('/warehouse/{bicycle_part}/edit', [\App\Http\Controllers\WarehousePartController::class, 'edit'])->name('warehouse.edit');
    Route::put('/warehouse/{bicycle_part}', [\App\Http\Controllers\WarehousePartController::class, 'update'])->name('warehouse.update');
    Route::delete('/warehouse/{bicycle_part}', [\App\Http\Controllers\WarehousePartController::class, 'destroy'])->name('warehouse.destroy');
});

require __DIR__.'/auth.php';
