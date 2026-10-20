<?php

use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EpisodeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SeriesController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FileStorageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - HUMORA Portal Admin
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Authentication Routes
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Forgot & Reset Password Routes (Admin)
Route::get('/forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('admin.password.request');
Route::post('/forgot-password', [AdminAuthController::class, 'verifyForgotPasswordEmail'])->name('admin.password.email');
Route::get('/reset-password', [AdminAuthController::class, 'showResetPasswordForm'])->name('admin.password.reset');
Route::post('/reset-password', [AdminAuthController::class, 'resetPassword'])->name('admin.password.update');

// Admin Portal Routes (Requires Admin Authentication & RBAC)
Route::prefix('admin')->name('admin.')->middleware(['admin.role'])->group(function () {
    // 1. Overview (Accessible by all admin roles)
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/realtime-data', [AnalyticsController::class, 'realtimeData'])->name('analytics.realtime');

    // 2. Profile (Accessible by all logged-in admins)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // 3. Content Management (super_admin, content_admin)
    Route::middleware('admin.role:super_admin,content_admin')->group(function () {
        // Series (Judul Konten)
        Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
        Route::get('/series/create', [SeriesController::class, 'create'])->name('series.create');
        Route::post('/series', [SeriesController::class, 'store'])->name('series.store');
        Route::get('/series/{id}', [SeriesController::class, 'show'])->name('series.show');
        Route::get('/series/{id}/edit', [SeriesController::class, 'edit'])->name('series.edit');
        Route::put('/series/{id}', [SeriesController::class, 'update'])->name('series.update');
        Route::delete('/series/{id}', [SeriesController::class, 'destroy'])->name('series.destroy');

        // Episodes (Kelola Video)
        Route::get('/videos', [EpisodeController::class, 'index'])->name('videos.index');
        Route::get('/videos/create', [EpisodeController::class, 'create'])->name('videos.create');
        Route::post('/videos', [EpisodeController::class, 'store'])->name('videos.store');
        Route::get('/videos/{id}/edit', [EpisodeController::class, 'edit'])->name('videos.edit');
        Route::put('/videos/{id}', [EpisodeController::class, 'update'])->name('videos.update');
        Route::delete('/videos/{id}', [EpisodeController::class, 'destroy'])->name('videos.destroy');

        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // 4. Audiens (Users & Subscribers)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{id}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus')
        ->middleware('admin.role:super_admin');

    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');

    // 5. Finance & Transactions (super_admin, finance_admin)
    Route::middleware('admin.role:super_admin,finance_admin')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::post('/transactions/{id}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
        Route::post('/transactions/{id}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
        Route::get('/proofs/{filename}', [FileStorageController::class, 'adminProof'])->name('proof.view');
    });

    // 6. System Administration (super_admin only)
    Route::middleware('admin.role:super_admin')->group(function () {
        // Admin Accounts Management
        Route::get('/admins', [AdminManagementController::class, 'index'])->name('admins.index');
        Route::post('/admins', [AdminManagementController::class, 'store'])->name('admins.store');
        Route::put('/admins/{admin}', [AdminManagementController::class, 'update'])->name('admins.update');
        Route::delete('/admins/{admin}', [AdminManagementController::class, 'destroy'])->name('admins.destroy');

        // System Settings & Monetization Config
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
