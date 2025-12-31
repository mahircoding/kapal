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
            // Rename npwp column to npwp_path and change type to string for file path
            $table->renameColumn('npwp', 'npwp_path');
        });
        
        // Change column type in a separate statement (required for some DB drivers)
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('npwp_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->renameColumn('npwp_path', 'npwp');
        });
        
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('npwp', 20)->nullable()->change();
        });
    }
};
