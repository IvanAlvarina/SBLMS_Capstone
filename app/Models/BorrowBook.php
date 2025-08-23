<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowBook extends Model
{
        protected $fillable = [
            'book_id',
            'user_id',
            'borrowed_at',
            'return_due_at',
            'status',
            'approved_at',
            'due_date',
        ];


        public function book()
        {
            return $this->belongsTo(BooksList::class, 'book_id');
        }

        public function user()
        {
            return $this->belongsTo(RegisteredUsers::class, 'user_id');
        }
}
