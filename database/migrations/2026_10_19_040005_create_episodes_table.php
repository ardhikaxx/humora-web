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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained('series')->cascadeOnDelete();
            $table->unsignedInteger('episode_number');
            $table->string('title');
            $table->text('synopsis')->nullable();
            $table->string('duration', 50)->default('2m 00s');
            $table->unsignedInteger('duration_seconds')->default(120);
            $table->string('video_file')->nullable();
            $table->enum('access_type', ['free', 'premium'])->default('free');
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();

            $table->unique(['series_id', 'episode_number']);
            $table->index(['series_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
