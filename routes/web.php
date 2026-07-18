<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('history', \App\Livewire\ActivityHistory::class)
    ->middleware(['auth', 'verified'])
    ->name('history');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('category-targets', \App\Http\Controllers\CategoryTargetController::class)->except(['create', 'show', 'edit']);
});

require __DIR__.'/auth.php';
