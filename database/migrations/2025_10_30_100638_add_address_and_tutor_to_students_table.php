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
        Schema::table('students', function (Blueprint $table) {
            $table->text('address')->nullable()->after('registration_number');
            $table->string('tutor_name')->nullable()->after('address');
            $table->string('tutor_phone')->nullable()->after('tutor_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['address', 'tutor_name', 'tutor_phone']);
        });
    }
};

