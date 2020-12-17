<?php

namespace App\Controllers;

use \Exception;
use App\Models\ContactUs;
use App\utils\Helper;

class ContactUsController
{

    public function insertContactUS($request, $response)
    {
        $data = $request->getParsedBody();
        $res = new ContactUS;
        $res->name  = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $res->email  = filter_var($data['email'], FILTER_SANITIZE_STRING);
        $res->message  = filter_var($data['message'], FILTER_SANITIZE_STRING);
        //  $res->created_at = time();
        // $res->updated_at = time();
        $result = $res->save();
        return $response->withJson($result);
    }
}
