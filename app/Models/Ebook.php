<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
     protected $fillable = [
        'name',
        'url',
        'is_active',
    ];
}
