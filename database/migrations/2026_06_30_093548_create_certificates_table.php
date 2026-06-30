<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            // Nullable link to a resident record (auto-filled)
            $table->foreignId('resident_id')->nullable()->constrained('residents')->nullOnDelete();

            // Personal info (may be filled manually or from resident)
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('address');

            // Certificate details
            $table->enum('certificate_type', [
                'barangay_clearance',
                'indigency',
                'residency',
                'certification',
            ])->default('barangay_clearance');

            $table->string('purpose')->nullable();

            // Status lifecycle: pending → ongoing → completed
            $table->enum('status', ['pending', 'ongoing', 'completed'])->default('pending');

            // Audit
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('issued_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
