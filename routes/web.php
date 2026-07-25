<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('history', 'history')
    ->middleware(['auth', 'verified'])
    ->name('history');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('category-targets', \App\Http\Controllers\CategoryTargetController::class)->except(['create', 'show', 'edit']);
    Route::post('categories', [\App\Http\Controllers\CategoryTargetController::class, 'storeCategory'])->name('categories.store');
    Route::put('categories/{category}', [\App\Http\Controllers\CategoryTargetController::class, 'updateCategory'])->name('categories.update');
    Route::delete('categories/{category}', [\App\Http\Controllers\CategoryTargetController::class, 'destroyCategory'])->name('categories.destroy');
});

require __DIR__.'/auth.php';
