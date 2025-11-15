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
        Schema::table('books_lists', function (Blueprint $table) {
            $table->string('cutter_sanborn')->nullable()->after('dewey_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books_lists', function (Blueprint $table) {
            $table->dropColumn('cutter_sanborn');
        });
    }
};
