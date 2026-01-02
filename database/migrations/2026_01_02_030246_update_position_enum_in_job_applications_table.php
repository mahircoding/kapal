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
            // Change position from ENUM to VARCHAR to support all positions
            $table->string('position', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Revert back to original ENUM with 4 positions
            $table->enum('position', ['Master', 'Chief Officer', 'Able Seaman', 'Cook'])->nullable()->change();
        });
    }
};
