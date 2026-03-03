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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // 1. Blindaje de Dominios (Enums)
            $table->enum('transaction_type', ['sale', 'exchange']);
            $table->enum('status', ['pending', 'completed', 'cancelled', 'in_progress'])->default('pending');

            $table->date('transaction_date');
            $table->decimal('amount', 8, 2)->nullable();
            $table->string('payment_method')->nullable();

            // 2. Integridad Referencial y Auditoría Financiera
            $table->foreignId('book_id')->constrained('books')->restrictOnDelete();

            // Especificamos que apuntan a la tabla 'users'
            $table->foreignId('buyer_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('seller_id')->constrained('users')->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};