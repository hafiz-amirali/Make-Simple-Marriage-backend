<?php

namespace App\Controllers;

use \Exception;
use App\Models\Candidate;
use App\utils\Helper;
use App\Models\Enums\StatusCode;

class AdminController
{
    public function unapprovedUsers($request, $response)
    {
        $res = Candidate::where("status", "=", false)
        ->leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
        ->select(
            'candidates.*',
            'qualification.*'
        )
        ->get();
        return $response->withJson($res);
    }
    
}
