<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\PeriodController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [RegistrationController::class, 'create'])->name('register.create');
    Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
        Route::resource('periods', PeriodController::class);
        Route::get('/registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');
        Route::get('/registrations/{Registration}', [AdminRegistrationController::class, 'show'])->name('registrations.show');
        Route::get('/registrations/{Registration}/approveForm', [AdminRegistrationController::class, 'approveForm'])->name('registrations.approveForm');
        Route::post('/registrations/{Registration}/approve', [AdminRegistrationController::class, 'approve'])->name('registrations.approve');
        Route::post('/registrations/{Registration}/reject', [AdminRegistrationController::class, 'reject'])->name('registrations.reject');
    });

    Route::middleware('role:member')->prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', fn () => view('member.dashboard'))->name('dashboard');
    });

    Route::middleware('role:candidate_member')->prefix('candidate')->name('candidate.')->group(function () {
        Route::get('/dashboard', fn () => view('candidate.dashboard'))->name('dashboard');
    });
});