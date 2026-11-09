<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations to normalize all existing user phone numbers to '08...' format.
     */
    public function up(): void
    {
        $users = DB::table('users')
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->get(['id', 'phone']);

        foreach ($users as $user) {
            $normalized = User::normalizePhoneNumber($user->phone);
            if ($normalized && $normalized !== $user->phone) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['phone' => $normalized]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Normalization to standard '08...' format is intended and irreversible.
    }
};
