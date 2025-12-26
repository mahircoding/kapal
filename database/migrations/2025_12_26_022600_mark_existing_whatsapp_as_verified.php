<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mark existing users with WhatsApp numbers as verified
        // This is for backward compatibility with existing users
        DB::table('users')
            ->whereNotNull('whatsapp_number')
            ->whereNull('whatsapp_verified_at')
            ->update([
                'whatsapp_verified_at' => now()
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally, you can reset the verification status
        // DB::table('users')->update(['whatsapp_verified_at' => null]);
    }
};
