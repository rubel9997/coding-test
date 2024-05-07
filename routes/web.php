<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'verified'])->group(function(){

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/deposit', [TransactionController::class, 'showDepositedTransactions'])->name('deposit');
    Route::post('/deposit', [TransactionController::class, 'deposit']);
    Route::get('/withdrawal', [TransactionController::class, 'showWithdrawalTransactions'])->name('withdrawal');
    Route::post('/withdrawal', [TransactionController::class, 'withdrawal']);
});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
