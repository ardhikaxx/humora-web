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
        // 1. Add plan_type and video_quota to subscription_plans
        Schema::table('subscription_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_plans', 'plan_type')) {
                $table->string('plan_type', 30)->default('weekly')->after('code');
            }
            if (!Schema::hasColumn('subscription_plans', 'video_quota')) {
                $table->unsignedInteger('video_quota')->nullable()->after('duration_days');
            }
        });

        // 2. Add plan_type to subscriptions table if needed
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'plan_type')) {
                $table->string('plan_type', 30)->default('weekly')->after('plan_id');
            }
            if (!Schema::hasColumn('subscriptions', 'video_quota')) {
                $table->unsignedInteger('video_quota')->nullable()->after('plan_type');
            }
        });

        // 3. Create user_video_accesses table to track allocated videos
        if (!Schema::hasTable('user_video_accesses')) {
            Schema::create('user_video_accesses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('episode_id')->constrained('episodes')->onDelete('cascade');
                $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('cascade');
                $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('set null');
                $table->dateTime('granted_at');
                $table->dateTime('expires_at');
                $table->timestamps();

                $table->index(['user_id', 'episode_id']);
                $table->index(['user_id', 'expires_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_video_accesses');

        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'video_quota')) {
                $table->dropColumn('video_quota');
            }
            if (Schema::hasColumn('subscriptions', 'plan_type')) {
                $table->dropColumn('plan_type');
            }
        });

        Schema::table('subscription_plans', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_plans', 'video_quota')) {
                $table->dropColumn('video_quota');
            }
            if (Schema::hasColumn('subscription_plans', 'plan_type')) {
                $table->dropColumn('plan_type');
            }
        });
    }
};
