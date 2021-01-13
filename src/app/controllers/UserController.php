<?php

namespace App\Controllers;

use \Exception;
use App\Models\Candidate;
use App\Models\User;
use App\utils\Helper;
use App\Utils\Utils;
use App\Models\Enums\StatusCode;

class UserController
{
    public function authenticateUser($request, $response)
    {
        try {
            $data = $request->getParsedBody();
            if (!empty($data['email']) && !empty($data['password'])) {
                $result = Candidate::where('password', $data['password'])
                //->orWhere('user_name', $data['email'])
                ->select('id', 'name', 'email', 'status');

                $result = $result->first();

                if (!empty($result) && $result != null) {
                    $result["dateFormat"] = $_REQUEST['dateFormat'];
                    $data = $result->toArray();
                    $userRoles["token"] = Utils::getJWTToken(json_encode($data));
                    $userRoles["dateFormat"] = $result["dateFormat"];
                    return $response->withJson($userRoles);
                } else {
                    return $response->withStatus(401)->withJson(array('success' => false, 'message' => 'email / passwors is incorrect'));
                }
            }
            return $response->withStatus(401)->withJson(array('success' => false, 'message' => 'email / passwors is missing'));
        } catch (Exception $e) {
            return $response->withStatus(500)
                ->withHeader('Content-Type', 'text/html')
                ->write($e);
        }
    }
    
}
