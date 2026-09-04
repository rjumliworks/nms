<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index']);
});

Route::middleware(['role:Administrator'])->group(function () {
    Route::resource('/users', App\Http\Controllers\Executive\UserController::class);

    Route::resource('/names', App\Http\Controllers\Modules\Trips\NameController::class)->only(['index','store','update']);
    Route::resource('/trips', App\Http\Controllers\Modules\Trips\TripController::class);
    Route::resource('/carriers', App\Http\Controllers\Modules\Trips\CarrierController::class)->only(['store']);
    Route::resource('/expenses', App\Http\Controllers\Modules\Trips\ExpenseController::class);
    Route::resource('/cash-advances', App\Http\Controllers\Modules\Trips\LoanController::class);
    Route::resource('/sales', App\Http\Controllers\Modules\Trips\SaleController::class);
});

require __DIR__.'/auth.php';
