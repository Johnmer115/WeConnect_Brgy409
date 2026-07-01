<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Resident\AnnouncementController as ResidentAnnouncementController;
use App\Http\Controllers\Resident\HomeController as ResidentHomeController;
use App\Http\Controllers\Resident\ReportController as ResidentReportController;

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
    Route::get('/home', ResidentHomeController::class)->middleware('role:resident')->name('home');
    Route::middleware(['role:resident', 'resident.approved'])->group(function () {
        Route::get('/announcements', ResidentAnnouncementController::class)->name('resident.announcements');
        Route::get('/reports', ResidentReportController::class)->name('resident.reports');
    });

    // Admin-only (any of these three roles)
    Route::middleware('role:secretary,chairman,kagawad')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, '__invoke'])->name('dashboard');
            Route::post('/dashboard/banner', [DashboardController::class, 'uploadBanner'])->name('dashboard.banner.upload');
            Route::delete('/dashboard/banner', [DashboardController::class, 'deleteBanner'])->name('dashboard.banner.delete');
            Route::get('/dashboard/events', [DashboardController::class, 'events'])->name('dashboard.events');
            Route::resource('announcements', AdminAnnouncementController::class)->except(['show']);
            Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
            Route::get('/certificates/resident-lookup', [CertificateController::class, 'residentLookup'])->name('certificates.residentLookup');
            Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
            Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
            Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');
            Route::get('/certificates/{certificate}/edit', [CertificateController::class, 'edit'])->name('certificates.edit');
            Route::put('/certificates/{certificate}', [CertificateController::class, 'update'])->name('certificates.update');
            Route::delete('/certificates/{certificate}', [CertificateController::class, 'destroy'])->name('certificates.destroy');
            Route::patch('/certificates/{certificate}/status', [CertificateController::class, 'updateStatus'])->name('certificates.updateStatus');
            Route::get('/certificates/{certificate}/print', [CertificateController::class, 'print'])->name('certificates.print');
            Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
            Route::get('/accounts/create', [AccountController::class, 'create'])->name('accounts.create');
            Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
            Route::get('/accounts/{account}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
            Route::put('/accounts/{account}', [AccountController::class, 'update'])->name('accounts.update');
            Route::delete('/accounts/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');
            Route::post('/residents/{resident}/verify', [ResidentController::class, 'verify'])->name('residents.verify');
            Route::resource('residents', ResidentController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
            Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity.index');
        });
});
