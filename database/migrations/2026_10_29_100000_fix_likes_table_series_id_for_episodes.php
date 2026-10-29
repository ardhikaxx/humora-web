<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For all episode likes, ensure series_id is null so that unique(user_id, series_id)
        // is never triggered across different episodes of the same series.
        DB::table('likes')->whereNotNull('episode_id')->update(['series_id' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-populate series_id from episodes table if rolled back
        if (Schema::hasTable('likes') && Schema::hasTable('episodes')) {
            $episodes = DB::table('episodes')->select('id', 'series_id')->get();
            foreach ($episodes as $ep) {
                DB::table('likes')
                    ->where('episode_id', $ep->id)
                    ->update(['series_id' => $ep->series_id]);
            }
        }
    }
};
