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
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('autor');
            $table->string('editorial')->nullable();
            $table->integer('anio_editorial')->nullable();
            $table->foreignId('id_categoria')->constrained('categorias')->onDelete('cascade');
            $table->text('descripcion')->nullable();
            $table->string('estado_fisico')->nullable();
            $table->string('tipo_operacion'); // venta, intercambio, donación
            $table->decimal('precio', 8, 2)->nullable();
            $table->date('fecha_publicacion')->nullable();
            $table->string('disponibilidad')->default('disponible');
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
