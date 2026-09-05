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
    Route::resource('/buyers', App\Http\Controllers\Modules\Trips\BuyerController::class)->only(['index','store','update']);
    Route::resource('/employees', App\Http\Controllers\Modules\Trips\EmployeeController::class)->only(['index','store','update']);
    Route::resource('/fish-species', App\Http\Controllers\Modules\Trips\FishSpeciesController::class)->only(['index','store','update']);
    Route::resource('/trips', App\Http\Controllers\Modules\Trips\TripController::class);
    Route::resource('/carriers', App\Http\Controllers\Modules\Trips\CarrierController::class)->only(['store']);
    Route::get('/expenses/print', [App\Http\Controllers\Modules\Trips\ExpenseController::class, 'print']);
    Route::resource('/expenses', App\Http\Controllers\Modules\Trips\ExpenseController::class);
    Route::get('/cash-advances/print', [App\Http\Controllers\Modules\Trips\LoanController::class, 'print']);
    Route::resource('/cash-advances', App\Http\Controllers\Modules\Trips\LoanController::class);
    Route::get('/sales/print', [App\Http\Controllers\Modules\Trips\SaleController::class, 'print']);
    Route::resource('/sales', App\Http\Controllers\Modules\Trips\SaleController::class);
});

require __DIR__.'/auth.php';
