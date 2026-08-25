<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
    });

    Route::middleware('role:member')->prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', fn () => view('member.dashboard'))->name('dashboard');
    });

    Route::middleware('role:candidate_member')->prefix('candidate')->name('candidate.')->group(function () {
        Route::get('/dashboard', fn () => view('candidate.dashboard'))->name('dashboard');
    });
});