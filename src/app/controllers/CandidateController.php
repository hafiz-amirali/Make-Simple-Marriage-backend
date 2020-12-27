<?php

namespace App\Controllers;

use \Exception;
use App\Models\Candidate;
use App\Models\Qualification;
use App\utils\Helper;
use App\Models\Enums\StatusCode;

class CandidateController
{

    public function getAllCandidates($request, $response)
    {
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
        $page_size = (isset($_GET['page_size']) && $_GET['page_size'] > 0) ? $_GET['page_size'] : Helper::defaultPageSize();
        $res = Candidate::where("status", "=", true);
        if (isset($_GET['sort_by']) && isset($_GET['is_desc'])) {
            $res = $res->orderBy($_GET['sort_by'], $_GET['is_desc']);
        }
        return $response->withJson($res->paginate($page_size, ['*'], 'page', $page));
    }

    public function getAllWithoutPagination($request, $response)
    {
        $res = Candidate::where("status", "=", true)->get();
        return $response->withJson($res);
    }

    
    public function search($request, $response)
    {

        $data = $request->getParsedBody();

        $city  = filter_var($data['city'], FILTER_SANITIZE_STRING);
        $cast  = filter_var($data['cast'], FILTER_SANITIZE_STRING);
        $sect  = filter_var($data['sect'], FILTER_SANITIZE_STRING);
        $age  = filter_var($data['age'], FILTER_SANITIZE_STRING);
        $education  = filter_var($data['education'], FILTER_SANITIZE_STRING);
        
        if($city !== '' && $cast !== '' && $sect !== '' && $age !== '' && $education !== ''){
            
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['cast', "like", "%" . $cast . "%"],
                ['sect', "like", "%" . $sect . "%"],
                ['age', "like", "%" . $age . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
                
        }

        

        if($city !== '' && $sect !== '' && $age !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['sect', "like", "%" . $sect . "%"],
                ['age', "like", "%" . $age . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($city !== '' && $cast !== '' && $age !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['cast', "like", "%" . $cast . "%"],
                ['age', "like", "%" . $age . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($city !== '' && $cast !== '' && $sect !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['cast', "like", "%" . $cast . "%"],
                ['sect', "like", "%" . $sect . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($city !== '' && $cast !== '' && $sect !== '' && $age !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['cast', "like", "%" . $cast . "%"],
                ['sect', "like", "%" . $sect . "%"],
                ['age', "like", "%" . $age . "%"]
                ])->get();
               
             return $response->withJson($res);
        }
        
        if($cast !== '' && $sect !== '' && $age !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['cast', "like", "%" . $cast . "%"],
                ['sect', "like", "%" . $sect . "%"],
                ['age', "like", "%" . $age . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($city !== '' && $sect !== '' && $age !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['sect', "like", "%" . $sect . "%"],
                ['age', "like", "%" . $age . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($city !== '' && $age !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['age', "like", "%" . $age . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }
        if($city !== '' && $sect !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['sect', "like", "%" . $sect . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($city !== '' && $cast !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['cast', "like", "%" . $cast . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($city !== '' && $cast !== '' && $age !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['cast', "like", "%" . $cast . "%"],
                ['age', "like", "%" . $age . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($city !== '' && $cast !== '' && $sect !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['cast', "like", "%" . $cast . "%"],
                ['sect', "like", "%" . $sect . "%"]
                ])->get();
               
             return $response->withJson($res);
        }
        
        if($sect !== '' && $age !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['sect', "like", "%" . $sect . "%"],
                ['age', "like", "%" . $age . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($cast !== '' && $sect !== '' && $age !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['cast', "like", "%" . $cast . "%"],
                ['sect', "like", "%" . $sect . "%"],
                ['age', "like", "%" . $age . "%"]
                ])->get();
               
             return $response->withJson($res);
        }
        
        if($cast !== '' && $sect !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['cast', "like", "%" . $cast . "%"],
                ['sect', "like", "%" . $sect . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($cast !== '' && $age !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['cast', "like", "%" . $cast . "%"],
                ['age', "like", "%" . $age . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }
        
        if($city !== '' && $cast !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['cast', "like", "%" . $cast . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($city !== '' && $sect !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['sect', "like", "%" . $sect . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($city !== '' && $age !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['age', "like", "%" . $age . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($city !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['city', "like", "%" . $city . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($cast !== '' && $sect !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['cast', "like", "%" . $cast . "%"],
                ['sect', "like", "%" . $sect . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($cast !== '' && $age !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['cast', "like", "%" . $cast . "%"],
                ['age', "like", "%" . $age . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($cast !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['cast', "like", "%" . $cast . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($sect !== '' && $age !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['sect', "like", "%" . $sect . "%"],
                ['age', "like", "%" . $age . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($sect !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['sect', "like", "%" . $sect . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($age !== '' && $education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where([
                ['age', "like", "%" . $age . "%"],
                ['degree', "like", "%" . $education . "%"]
                ])->get();
               
             return $response->withJson($res);
        }

        if($city !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where('city', "like", "%" . $city . "%")->get();
               
             return $response->withJson($res);
        }

        if($cast !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where('cast', "like", "%" . $cast . "%")->get();
               
             return $response->withJson($res);
        }

        if($sect !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where('sect', "like", "%" . $cast . "%")->get();
               
             return $response->withJson($res);
        }

        if($age !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where('age', "like", "%" . $cast . "%")->get();
               
             return $response->withJson($res);
        }

        if($education !== ''){
            $res = Candidate::leftJoin('qualification', 'candidates.qualification_id', '=', 'qualification.id')
            ->select(
                'candidates.*',
                'qualification.*'
            )
            ->where('degree', "like", "%" . $cast . "%")->get();
               
             return $response->withJson($res);
        }

            return $response->withJson('not matched');
    }
    
    public function insertCandidate($request, $response){
        $data = $request->getParsedBody();
        $qualification = array(
            "degree"=>$data['degree'],
            "year"=>$data['year'],
            "board_name"=>$data['board'],
            "institute_name"=>$data['institute']
        );

        $result = Qualification::create($qualification);
        $data['qualification_id'] = $result->id;
        //return $response->withJson($data['cast']);
        $result = Candidate::create($data);
        return $response->withJson(array('success' => true, 'statusCode' => StatusCode::ok, 'last_inserted_id' => $result->id));
    }

    
}
