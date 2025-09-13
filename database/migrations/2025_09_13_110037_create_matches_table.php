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
        Schema::create('matches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('tutor_id')->constrained()->onDelete('cascade');
            $table->decimal('compatibility_score', 5, 2); // Score de compatibilité sur 100
            $table->json('matching_details'); // Détails du match (matières, créneaux communs, etc.)
            $table->enum('status', ['suggested', 'accepted', 'rejected'])->default('suggested');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
