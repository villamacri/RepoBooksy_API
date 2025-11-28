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
        Schema::create('participacion_evento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_participacion')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_evento')->constrained('eventos')->onDelete('cascade');
            $table->date('fecha_inscripcion');
            $table->string('estado')->default('confirmado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participacion_evento');
    }
};
