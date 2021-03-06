<?php

namespace App\Controllers;

use \Exception;
use App\Models\Candidate;
use App\Models\Article;
use App\Models\User;
use App\utils\Helper;
use App\Utils\Utils;
use App\Models\Enums\StatusCode;

class AdminController
{
    public function unapprovedUsers($request, $response)
    {
        $fields_to_remove = ['password', 'status'];

        $res = Candidate::where("status", "=", false)
        ->leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
        ->select(
            'candidates.*',
            'qualification.id as qualification.id',
            "qualification.degree",
            "qualification.year",
            "qualification.board_name",
            "qualification.institute_name"
        )
        ->get();
        $res->makeHidden($fields_to_remove);
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

    public function approvedPosts($request, $response)
    {
        $res = Article::where("status", "=", true)->get();
        return $response->withJson($res);
    }

    public function unapprovedPosts($request, $response)
    {
        $res = Article::where("status", "=", false)->get();
        return $response->withJson($res);
    }

    public function approvePost($request, $response)
    {
        $data = $request->getParsedBody();
        if(!empty($data['id'])){
            $res = Article::where("id", "=", $data['id'])
            ->update(['status' => true]);
            if($res != false){
                return $response->withJson($res);
            }
            return $response->withJson("Record not found");
        }else{
            return $response->withJson("Id are Status not found");
        }
        
    }

    public function unpublishPost($request, $response)
    {
        $data = $request->getParsedBody();
        if(!empty($data['id'])){
            $res = Article::where("id", "=", $data['id'])
            ->update(['status' => false]);
            if($res != false){
                return $response->withJson($res);
            }
            return $response->withJson("Record not found");
        }else{
            return $response->withJson("Id are Status not found");
        }
        
    }

    public function updatePost($request, $response)
    {
        $data = $request->getParsedBody();
        if (!empty($data['id'])) {
            $res = Article::find($data['id']);
            if ($res != null) {
                $result = $res->update($data);
                return $response->withJson($result);
            }
            return $response->withJson("Article not found");
        }else{
            return $response->withJson("Id not found");
        }
        
    }
}
