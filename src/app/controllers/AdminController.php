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
    public function approveUser($request, $response)
    {
        $data = $request->getParsedBody();
        if(!empty($data['id']) && !empty($data['status']) ){
            $res = Candidate::where("id", "=", $data['id'])
            ->update(['status' => $data['status']]);
            if($res != false){
                return $response->withJson($res);
            }
            return $response->withJson("Record not found");
        }else{
            return $response->withJson("Id are Status not found");
        }
        
    }
    
}
