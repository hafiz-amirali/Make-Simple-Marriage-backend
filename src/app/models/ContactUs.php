<?php

namespace App\Models;

use Carbon\Carbon;

class ContactUs extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'contact_us';
    protected $dateFormat = 'U';
    protected $fillable = [
        "name",
        "email",
        "message"
    ];
    

}


   