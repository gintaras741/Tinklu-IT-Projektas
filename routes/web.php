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

// Parts Browsing (authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/parts', [\App\Http\Controllers\PartsController::class, 'index'])->name('parts.index');
});

// My Bicycles (authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/bicycles', [\App\Http\Controllers\MyBicycleController::class, 'index'])->name('bicycles.index');
    Route::get('/bicycles/create', [\App\Http\Controllers\MyBicycleController::class, 'create'])->name('bicycles.create');
    Route::post('/bicycles', [\App\Http\Controllers\MyBicycleController::class, 'store'])->name('bicycles.store');
    Route::get('/bicycles/{bicycle}', [\App\Http\Controllers\MyBicycleController::class, 'show'])->name('bicycles.show');
    Route::get('/bicycles/{bicycle}/edit', [\App\Http\Controllers\MyBicycleController::class, 'edit'])->name('bicycles.edit');
    Route::put('/bicycles/{bicycle}', [\App\Http\Controllers\MyBicycleController::class, 'update'])->name('bicycles.update');
    Route::delete('/bicycles/{bicycle}', [\App\Http\Controllers\MyBicycleController::class, 'destroy'])->name('bicycles.destroy');
});

// Cart (authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/part/{part}', [\App\Http\Controllers\CartController::class, 'addPart'])->name('cart.addPart');
    Route::post('/cart/bicycle/{bicycle}', [\App\Http\Controllers\CartController::class, 'addBicycle'])->name('cart.addBicycle');
    Route::delete('/cart/{cart}/part/{part}', [\App\Http\Controllers\CartController::class, 'removePart'])->name('cart.removePart');
    Route::delete('/cart/{cart}/bicycle/{bicycle}', [\App\Http\Controllers\CartController::class, 'removeBicycle'])->name('cart.removeBicycle');
    Route::delete('/cart', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
});

// Orders (authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [\App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
});

// Alerts (authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/alerts', [\App\Http\Controllers\AlertController::class, 'index'])->name('alerts.index');
    Route::delete('/alerts/{alert}', [\App\Http\Controllers\AlertController::class, 'destroy'])->name('alerts.destroy');
    Route::delete('/alerts', [\App\Http\Controllers\AlertController::class, 'destroyAll'])->name('alerts.destroyAll');
});

// Questions/FAQ (authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/questions', [\App\Http\Controllers\QuestionController::class, 'index'])->name('questions.index');
    Route::get('/questions/create', [\App\Http\Controllers\QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [\App\Http\Controllers\QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions/{question}', [\App\Http\Controllers\QuestionController::class, 'show'])->name('questions.show');
    Route::delete('/questions/{question}', [\App\Http\Controllers\QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::post('/questions/{question}/answers', [\App\Http\Controllers\QuestionController::class, 'storeAnswer'])->name('questions.answers.store');
    Route::delete('/questions/{question}/answers/{answer}', [\App\Http\Controllers\QuestionController::class, 'destroyAnswer'])->name('questions.answers.destroy');
});

// Admin Orders (admin & worker only)
Route::middleware(['auth', 'role:admin,worker'])->prefix('admin')->group(function () {
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/statistics', [\App\Http\Controllers\Admin\OrderController::class, 'statistics'])->name('admin.orders.statistics');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
});

// Admin User Management (admin only)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
});

require __DIR__.'/auth.php';
