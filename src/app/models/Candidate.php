<?php

namespace App\Models;

use Carbon\Carbon;

class Candidate extends \Illuminate\Database\Eloquent\Model
{

    protected $dateFormat = 'U';
    protected $fillable = [
        "name",
        "email",
        "contact",
        "password",
        "dob",	
        "gender",
        "address",
        "religion",
        "sect",
        "city",
        "age",
        "job_status",
        "income",
        "cast",
        "mother_tongue",
        "nation_id",
        "qualification_id",
        "marriage_status",
        "child_detail",
        "status"
    ];

    public function qualification()
    {
        return $this->hasOne('App\Models\Qualification');
    }
}