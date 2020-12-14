<?php

namespace App\Controllers;

use \Exception;
use App\Models\Candidate;
use App\utils\Helper;

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
}
