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
        Schema::create('meetup_attendances', function (Blueprint $table) {
            $table->id();
            
            // Traducido a enrollment_date
            $table->date('enrollment_date'); 
            
            // Usamos enum por seguridad en lugar de string abierto
            $table->enum('status', ['confirmed', 'cancelled', 'waitlisted'])->default('confirmed');
            
            // Claves foráneas bien enlazadas y con borrado en cascada
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('meetup_id')->constrained('meetups')->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetup_attendances');
    }
};