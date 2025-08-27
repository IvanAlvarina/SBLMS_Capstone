<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsMagazine extends Model
{
    protected $fillable = [
        'name',
        'url',
        'is_active',
    ];
}
