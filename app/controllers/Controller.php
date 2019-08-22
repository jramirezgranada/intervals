<?php

namespace App\Controllers;

use App\Models\ApiUser;

class Controller
{
    /**
     * Check if sent token is valid
     * @param $req
     * @param $resp
     * @return mixed
     */
    public function checkAuthToken($req, $resp)
    {
        $headers = $req->headers();

        if (!isset($headers["Token"])) {
            return $resp->json([
                "status" => "error",
                "code" => 403,
                "message" => "Authorization Bearer token is required"
            ]);
        }

        $token = str_replace("Bearer ", "", $headers["Token"]);

        $apiUser = ApiUser::whereToken($token)->first();

        if (!$apiUser) {
            return $resp->json([
                "status" => "error",
                "code" => 403,
                "message" => "Token is invalid, please try again"
            ]);
        }
    }
}