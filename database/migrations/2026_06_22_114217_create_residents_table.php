<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();

            // FK to users — nullable because admin can encode without a login account
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Basic Information
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();           // N/A, Jr, Sr, II, III
            $table->date('date_of_birth');
            $table->string('blood_type')->nullable();       // A+, A-, B+, B-, O+, O-, AB+, AB-
            $table->string('gender');                       // Male, Female
            $table->string('religion')->nullable();
            $table->string('health_status')->default('Alive'); // Alive, Deceased
            $table->date('date_deceased')->nullable();

            // Membership / Categories (checkboxes from the form)
            $table->boolean('is_4ps')->default(false);       // Pantawid Pamilyang Pilipino
            $table->boolean('is_pwd')->default(false);       // Person with Disability
            $table->boolean('is_voter')->default(false);     // Comelec Registered Voter
            $table->boolean('is_single_parent')->default(false);

            // Contact Information
            $table->string('email')->nullable();
            $table->string('telephone_number')->nullable();
            $table->string('mobile_number')->nullable();

            // Home Address Information
            $table->text('home_address')->nullable();
            $table->foreignId('purok_id')->nullable();
            // barangay/city and country are fixed for Brgy 409 — no need to store

            // Admin control
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
