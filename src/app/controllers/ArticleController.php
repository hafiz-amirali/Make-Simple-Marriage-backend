<?php

namespace App\Controllers;

use \Exception;
use App\Models\Article;
use App\utils\Helper;

class ArticleController
{

    public function getAllArticles($request, $response)
    {
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
        $page_size = (isset($_GET['page_size']) && $_GET['page_size'] > 0) ? $_GET['page_size'] : Helper::defaultPageSize();
        $res = Article::where("status", "=", true);
        if (isset($_GET['sort_by']) && isset($_GET['is_desc'])) {
            $res = $res->orderBy($_GET['sort_by'], $_GET['is_desc']);
        }
        return $response->withJson($res->paginate($page_size, ['*'], 'page', $page));
    }

    public function getAllWithoutPagination($request, $response)
    {
        $res = Article::where("status", "=", true)->get();
        return $response->withJson($res);
    }

    public function recentPosts($request, $response)
    {
        $res = Article::orderBy('id', 'desc')->select('title')->take(5)->get();
        return $response->withJson($res);
    }


    public function search($request, $response, $args)
    {
        if (!empty($args['search'])) {

            $res = Article::where('article', "like", "%" . $args["search"] . "%")->get();
            if ($res != null) {
                return $response->withJson($res);
            } else {
                return Helper::error('Something Went Wrong.', 500, $response);
            }
        } else {
            return Helper::error('Something Went Wrong.', 500, $response);
        }
    }
    
}
