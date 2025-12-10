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
        Schema::create('participacion_eventos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_inscripcion');
            $table->string('estado')->default('confirmado');
            $table->foreignId('user_id');
            $table->foreignId('evento_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participacion_eventos');
    }
};
