<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaccions', function (Blueprint $table) {
            $table->id();

            // 1. Blindaje de Dominios (Enums)
            $table->enum('tipo_transaccion', ['venta', 'intercambio']);
            $table->enum('estado', ['pendiente', 'completada', 'cancelada', 'en proceso'])->default('pendiente');

            $table->date('fecha_transaccion');
            $table->decimal('monto', 8, 2)->nullable();
            $table->string('metodo_pago')->nullable();

            // 2. Integridad Referencial y Auditoría Financiera
            // Usamos restrictOnDelete() para que no se puedan borrar entidades con historial de transacciones.
            $table->foreignId('libro_id')->constrained('libros')->restrictOnDelete();

            // Como Laravel asume que la tabla es 'compradors' (en plural inglés), 
            // debemos especificar explícitamente que apuntan a la tabla 'users'
            $table->foreignId('comprador_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('vendedor_id')->constrained('users')->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaccions');
    }
};
