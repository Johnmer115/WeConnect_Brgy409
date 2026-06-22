<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puroks', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // Purok 1, Purok 2 ...
            $table->string('color_code')->nullable(); // #FF5733 for color-coded UI
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puroks');
    }
};
