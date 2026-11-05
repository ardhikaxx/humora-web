<?php

use App\Models\Subscription;
use App\Models\UserVideoAccess;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Reset legacy pre-allocated video accesses for active weekly subscriptions
     * so that users receive their full dynamic 15-video quota to choose any video.
     */
    public function up(): void
    {
        $activeWeeklySubIds = Subscription::where('plan_type', 'weekly')
            ->where('status', 'active')
            ->pluck('id')
            ->all();

        if (! empty($activeWeeklySubIds)) {
            UserVideoAccess::whereIn('subscription_id', $activeWeeklySubIds)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
