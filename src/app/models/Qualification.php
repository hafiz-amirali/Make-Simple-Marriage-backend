<?php

namespace App\Models;

use Carbon\Carbon;

class Qualification extends \Illuminate\Database\Eloquent\Model
{

    protected $dateFormat = 'U';
    protected $table = 'qualification';
    protected $fillable = [
        "degree",
        "year",
        "board_name",
        "institute_name"
    ];

    public function candidate()
    {
        return $this->belongsTo('App\Models\Candidate');
    }
}