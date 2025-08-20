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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('text'); // The question text
            $table->text('answer')->nullable(); // Pre-defined answer
            $table->json('keywords')->nullable(); // Keywords for search functionality
            $table->integer('order')->default(0); // Order of questions
            $table->boolean('is_active')->default(true); // Active/inactive questions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};