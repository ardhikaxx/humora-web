<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Series Table Indexes (Catalog ordering and featured filtering)
        Schema::table('series', function (Blueprint $table) {
            $table->index(['status', 'catalog_order'], 'idx_series_status_catalog_order');
            $table->index(['status', 'is_featured'], 'idx_series_status_is_featured');
        });

        // 2. Episodes Table Indexes (Access type lookups & status timestamps)
        Schema::table('episodes', function (Blueprint $table) {
            $table->index(['series_id', 'access_type'], 'idx_episodes_series_access_type');
            $table->index(['status', 'created_at'], 'idx_episodes_status_created_at');
        });

        // 3. Subscription Plans Indexes (Active plans filter)
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->index('is_active', 'idx_plans_is_active');
        });

        // 4. Transactions Table Indexes (Revenue & conversion analytics by verification date)
        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['status', 'verified_at'], 'idx_transactions_status_verified_at');
        });

        // 5. Viewing Events Table Indexes (User history & session deduplication)
        Schema::table('viewing_events', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'idx_viewing_events_user_created');
            $table->index('session_id', 'idx_viewing_events_session_id');
        });

        // 6. Watch Histories Table Indexes (User's progress grouped by series)
        Schema::table('watch_histories', function (Blueprint $table) {
            $table->index(['user_id', 'series_id'], 'idx_watch_histories_user_series');
        });

        // 7. Users Table Indexes (Subscriber status & expiry checks)
        Schema::table('users', function (Blueprint $table) {
            $table->index(['status', 'tier'], 'idx_users_status_tier');
            $table->index('valid_until', 'idx_users_valid_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_status_tier');
            $table->dropIndex('idx_users_valid_until');
        });

        Schema::table('watch_histories', function (Blueprint $table) {
            $table->dropIndex('idx_watch_histories_user_series');
        });

        Schema::table('viewing_events', function (Blueprint $table) {
            $table->dropIndex('idx_viewing_events_user_created');
            $table->dropIndex('idx_viewing_events_session_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_transactions_status_verified_at');
        });

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropIndex('idx_plans_is_active');
        });

        Schema::table('episodes', function (Blueprint $table) {
            $table->dropIndex('idx_episodes_series_access_type');
            $table->dropIndex('idx_episodes_status_created_at');
        });

        Schema::table('series', function (Blueprint $table) {
            $table->dropIndex('idx_series_status_catalog_order');
            $table->dropIndex('idx_series_status_is_featured');
        });
    }
};
