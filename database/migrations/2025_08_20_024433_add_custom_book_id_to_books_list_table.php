<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('books_list', function (Blueprint $table) {
            $table->string('custom_book_id')->unique()->nullable()->after('book_id');
        });
    }

    public function down()
    {
        Schema::table('books_list', function (Blueprint $table) {
            $table->dropColumn('custom_book_id');
        });
    }
};

