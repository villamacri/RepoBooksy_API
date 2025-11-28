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
        Schema::create('usuario_normal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario_normal')->constrained('users')->onDelete('cascade');
            $table->string('reputacion')->nullable();
            $table->text('preferencias_categoria')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_normal');
    }
};
