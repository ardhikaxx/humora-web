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
        // Ubah tipe kolom role menjadi string varchar(30) dengan default 'admin'
        Schema::table('admins', function (Blueprint $table) {
            $table->string('role', 30)->default('admin')->change();
        });

        // Update seluruh admin yang ada menjadi role 'admin'
        DB::table('admins')->update(['role' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'content_admin', 'finance_admin'])->default('content_admin')->change();
        });
    }
};
