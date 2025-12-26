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
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('npwp', 20)->nullable()->after('religion');
            $table->string('buku_pelaut_path')->nullable()->after('medical_certificate_path');
            $table->string('account_data_path')->nullable()->after('buku_pelaut_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['npwp', 'buku_pelaut_path', 'account_data_path']);
        });
    }
};
