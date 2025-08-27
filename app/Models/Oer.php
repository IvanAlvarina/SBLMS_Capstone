<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oer extends Model
{
    protected $fillable = [
        'name',
        'url',
        'is_active',
    ];
}
