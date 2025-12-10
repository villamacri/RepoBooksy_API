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
        Schema::create('transaccions', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_transaccion');
            $table->date('fecha_transaccion');
            $table->decimal('monto', 8, 2)->nullable();
            $table->string('metodo_pago')->nullable();
            $table->string('estado')->default('pendiente');
            $table->foreignId('libro_id');
            $table->foreignId('comprador_id');
            $table->foreignId('vendedor_id');
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
