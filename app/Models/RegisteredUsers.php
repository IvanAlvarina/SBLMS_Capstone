<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RegisteredUsers extends Authenticatable
{
    protected $table = 'registered_users';

    protected $fillable = [
        'fullname',
        'student_no',
        'email',
        'address',
        'password',
        'role',
        'account_status',
        'profile_picture',
        'status',
        'first_login',
    ];
}
