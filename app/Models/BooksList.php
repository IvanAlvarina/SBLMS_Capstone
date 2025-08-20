<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BooksList extends Authenticatable
{
    protected $table = 'books_list';

    protected $primaryKey = 'book_id';
    public $incrementing = false;
    
    public $timestamps = false;
    
    protected $fillable = [
        'book_id',
        'book_title',
        'book_author',
        'book_genre',
        'book_yearpub',
        'book_isbn',
        'book_status',
        'book_cimage',
        'book_dateadded',
    ];
}










