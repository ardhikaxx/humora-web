<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - HUMORA Portal Admin
|--------------------------------------------------------------------------
*/

// Root route
Route::get('/', function () {
    return view('admin.dashboard');
});

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::get('/reset-password', function () {
    return view('auth.reset-password');
})->name('password.reset');

// Admin Portal Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Overview
    Route::get('/', function () {
        return view('admin.dashboard');
    });

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/analytics', function () {
        return view('admin.analytics');
    })->name('analytics');

    // Manajemen Konten - Judul Konten (Series)
    Route::get('/series', function () {
        return view('admin.series.index');
    })->name('series.index');

    Route::get('/series/create', function () {
        return view('admin.series.create');
    })->name('series.create');

    Route::get('/series/{id}', function ($id) {
        return view('admin.series.show', ['id' => $id]);
    })->name('series.show');

    Route::get('/series/{id}/edit', function ($id) {
        return view('admin.series.edit', ['id' => $id]);
    })->name('series.edit');

    // Manajemen Konten - Kelola Video
    Route::get('/videos', function () {
        return view('admin.videos.index');
    })->name('videos.index');

    Route::get('/videos/create', function () {
        return view('admin.videos.create');
    })->name('videos.create');

    Route::get('/videos/{id}/edit', function ($id) {
        return view('admin.videos.edit', ['id' => $id]);
    })->name('videos.edit');

    // Manajemen Konten - Kategori
    Route::get('/categories', function () {
        return view('admin.categories.index');
    })->name('categories.index');

    // Audiens & Pendapatan
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');

    Route::get('/subscribers', function () {
        return view('admin.subscribers.index');
    })->name('subscribers.index');

    Route::get('/transactions', function () {
        return view('admin.transactions.index');
    })->name('transactions.index');

    // Sistem
    Route::get('/admins', function () {
        return view('admin.admins.index');
    })->name('admins.index');

    Route::get('/profile', function () {
        return view('admin.profile.index');
    })->name('profile.index');

    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('settings.index');
});
