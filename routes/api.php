<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\EpisodeController;
use App\Http\Controllers\Api\V1\LikeController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\SeriesController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\VideoTrackingController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Api\V1\WatchHistoryController;
use App\Http\Controllers\FileStorageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HUMORA REST API Routes - Version 1 (/api/v1/)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // 1. Authentication (Public)
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPasswordVerify']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

    // 2. Catalog & Discovery (Public / Guest Allowed)
    Route::get('/series', [SeriesController::class, 'index']);
    Route::get('/series/{slugOrId}', [SeriesController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/episodes/{id}', [EpisodeController::class, 'show']);

    // Realtime Video Views & Watch Time Tracking (Public / Guest & Authenticated)
    Route::post('/videos/track-view', [VideoTrackingController::class, 'track']);
    Route::get('/analytics/realtime', [AnalyticsController::class, 'realtimeData']);

    // Social Likes (Public / Guest & Authenticated)
    Route::post('/series/{id}/like', [LikeController::class, 'toggleSeriesLike']);
    Route::post('/episodes/{id}/like', [LikeController::class, 'toggleEpisodeLike']);
    Route::get('/episodes/{id}/like-status', [LikeController::class, 'episodeLikeStatus']);

    // 3. Subscription Plans & Payment Information (Public)
    Route::get('/subscription/plans', [SubscriptionController::class, 'plans']);
    Route::get('/settings/payment-info', [SubscriptionController::class, 'paymentInfo']);

    // 4. Secure File Access & Streaming
    Route::get('/files/thumbnail/{filename}', [FileStorageController::class, 'publicThumbnail'])->name('file.thumbnail');
    Route::get('/files/stream/{episodeId}', [FileStorageController::class, 'streamEpisodeVideo'])->name('file.stream');
    Route::get('/files/system/{filename}', [FileStorageController::class, 'systemFile'])->name('file.system');

    // 5. Authenticated Mobile Routes (Requires Sanctum Token)
    Route::middleware('auth:sanctum')->group(function () {
        // User Profile & Account
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
        Route::put('/auth/password', [AuthController::class, 'updatePassword']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Subscriptions & Transactions
        Route::get('/subscription/status', [SubscriptionController::class, 'status']);
        Route::post('/subscription/checkout', [TransactionController::class, 'checkout']);
        Route::post('/transactions/upload-proof', [TransactionController::class, 'uploadProof']);
        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::get('/transactions/{id}', [TransactionController::class, 'show']);

        // Watch History
        Route::get('/watch-history', [WatchHistoryController::class, 'index']);
        Route::post('/watch-history/progress', [WatchHistoryController::class, 'updateProgress']);
        Route::delete('/watch-history', [WatchHistoryController::class, 'destroy']);

        // In-App Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    });
});
