<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->integer('publication_year')->nullable(); 
            $table->text('description')->nullable();

            $table->enum('physical_condition', ['new', 'like_new', 'good', 'acceptable', 'poor']);
            $table->enum('operation_type', ['sale', 'exchange', 'both']);

            $table->decimal('price', 8, 2)->nullable();
            $table->date('publication_date')->nullable();
            
            $table->boolean('is_available')->default(true); 

            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};