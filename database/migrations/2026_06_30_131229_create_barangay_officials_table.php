<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangay_officials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');                              // e.g. "Punong Barangay", "Kagawad"
            $table->enum('category', ['barangay', 'sk'])            // Barangay or SK group
                  ->default('barangay');
            $table->unsignedSmallInteger('sort_order')->default(0); // display order
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangay_officials');
    }
};
