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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario_autor')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_usuario_eval')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_transaccion')->constrained('transacciones')->onDelete('cascade');
            $table->text('comentario')->nullable();
            $table->integer('puntuacion')->unsigned(); // 1-5
            $table->date('fecha_feedback');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
