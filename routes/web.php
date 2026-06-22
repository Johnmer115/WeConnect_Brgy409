<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
    
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Resident-only
    Route::get('/home', fn () => view('home'))->middleware('role:resident')->name('home');

    // Admin-only (any of these three roles)
    Route::middleware('role:secretary,chairman,kagawad')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', DashboardController::class)->name('dashboard');
            Route::resource('residents', ResidentController::class)->only(['index', 'show']);
            Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
        });
});
