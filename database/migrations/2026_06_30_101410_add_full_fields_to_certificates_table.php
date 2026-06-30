<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            // Extended personal info
            $table->date('date_of_birth')->nullable()->after('suffix');
            $table->unsignedSmallInteger('age')->nullable()->after('date_of_birth');
            $table->string('gender')->nullable()->after('age');       // Male, Female
            $table->string('religion')->nullable()->after('gender');

            // Rename 'address' → keep it, add full address breakdown
            $table->string('purok')->nullable()->after('address');
            $table->string('barangay_city')->nullable()->after('purok');
            $table->string('country')->nullable()->default('Philippines')->after('barangay_city');

            // Contact information
            $table->string('email')->nullable()->after('country');
            $table->string('telephone')->nullable()->after('email');
            $table->string('mobile')->nullable()->after('telephone');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth', 'age', 'gender', 'religion',
                'purok', 'barangay_city', 'country',
                'email', 'telephone', 'mobile',
            ]);
        });
    }
};
