<?php
// Migration: schéma de pondération par cours (CW/QZ/EX en % totalisant 100).
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('grade_schemes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('cw_weight')->default(20); // Travaux en classe
            $table->unsignedTinyInteger('qz_weight')->default(30); // Interrogations
            $table->unsignedTinyInteger('ex_weight')->default(50); // Examens
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }
    public function down(): void {
        Schema::dropIfExists('grade_schemes');
    }
};
