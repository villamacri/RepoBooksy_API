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
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_libro')->constrained('libros')->onDelete('cascade');
            $table->foreignId('id_comprador')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_vendedor')->constrained('users')->onDelete('cascade');
            $table->string('tipo_transaccion'); // compra, intercambio, donación
            $table->date('fecha_transaccion');
            $table->decimal('monto', 8, 2)->nullable();
            $table->string('metodo_pago')->nullable();
            $table->string('estado')->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};
