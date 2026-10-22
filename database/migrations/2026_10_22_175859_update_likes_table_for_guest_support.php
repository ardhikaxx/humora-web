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
        Schema::table('likes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->string('session_id', 100)->nullable()->after('user_id');
            $table->string('ip_address', 45)->nullable()->after('session_id');

            $table->index(['session_id', 'episode_id']);
            $table->index(['session_id', 'series_id']);
            $table->index(['ip_address', 'episode_id']);
            $table->index(['ip_address', 'series_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->dropIndex(['session_id', 'episode_id']);
            $table->dropIndex(['session_id', 'series_id']);
            $table->dropIndex(['ip_address', 'episode_id']);
            $table->dropIndex(['ip_address', 'series_id']);

            $table->dropColumn(['session_id', 'ip_address']);
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
