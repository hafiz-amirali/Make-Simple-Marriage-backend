<?php

namespace App\Models;

use Carbon\Carbon;

class User extends \Illuminate\Database\Eloquent\Model
{

    protected $dateFormat = 'U';
    protected $fillable = [
        "name",
        "email",
        "password",
        "status"
    ];
}