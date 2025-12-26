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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Personal Info
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->enum('religion', ['Islam', 'Kristen', 'Hindu', 'Budha', 'Konghucu', 'Lainnya'])->nullable();
            
            // Address
            $table->text('address_ktp')->nullable();
            $table->text('domicile')->nullable();
            
            // Documents
            $table->string('ktp_path')->nullable();
            $table->string('kk_path')->nullable();
            $table->string('cv_path')->nullable(); // Made nullable as it might be uploaded later or optional in some flows? detailed request says nullable for others but CV was originally required. Keeping strict requirement in controller but nullable in DB for flexibility or adhering to user request snippet which had nullable.
            $table->string('certificate_path')->nullable(); // COP/COC
            $table->string('medical_certificate_path')->nullable();
            
            // Job Details
            $table->enum('position', ['Master', 'Chief Officer', 'Able Seaman', 'Cook'])->nullable(); // Using Enum as requested
            
            $table->text('cover_letter')->nullable();
            $table->text('admin_note')->nullable(); // For internal use
            $table->enum('status', ['pending', 'reviewed', 'rejected', 'accepted'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
