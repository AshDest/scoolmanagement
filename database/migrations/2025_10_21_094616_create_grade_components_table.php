<?php
// Migration: valeurs des composantes par inscription (en pourcentage 0..100).
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('grade_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('cw', 5, 2)->nullable(); // Travaux en classe
            $table->decimal('qz', 5, 2)->nullable(); // Interrogations
            $table->decimal('ex', 5, 2)->nullable(); // Examens
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }
    public function down(): void {
        Schema::dropIfExists('grade_components');
    }
};
