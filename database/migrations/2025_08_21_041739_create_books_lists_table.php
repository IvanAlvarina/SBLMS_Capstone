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
        Schema::create('books_lists', function (Blueprint $table) {
            $table->id('book_id');
            $table->string('book_title')->unique();
            $table->string('book_author')->nullable();
            $table->string('book_genre')->nullable();
            $table->date('book_yearpub')->nullable();
            $table->string('book_isbn')->unique();
            $table->string('book_status')->nullable();
            $table->string('book_cimage')->nullable();
            $table->string('custom_book_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books_lists');
    }
};
