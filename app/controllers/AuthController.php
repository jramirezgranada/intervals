<?php


namespace App\Controllers;


use App\Helpers\JWTHelper;
use App\Models\ApiUser;
use App\Validators\FormValidator;


class AuthController
{
    /**
     * Login username and password, to get token string
     * @param $request
     * @param $response
     * @return mixed
     */
    public function login($request, $response)
    {
        $data = $request->bodyToArray();

        $validations = (new FormValidator(
            $data,
            [
                "username" => "required",
                "password" => "required"
            ]
        ))->validate();

        if (isset($validations["data"])) {
            return $response->json($validations);
        }

        $apiUser = ApiUser::where('username', $data["username"])
            ->where('password', md5($data["password"]))
            ->first();

        if (!$apiUser) {
            return $response->json([
                "status" => "Error",
                "message" => "ApiUser Not found",
                "code" => 404,
                "data" => []
            ]);
        }

        $token = JWTHelper::signIn($data);
        $apiUser->token = $token;
        $apiUser->save();

        return $response->json([
            "status" => "Ok",
            "message" => "ApiUser Logged in",
            "code" => 200,
            "data" => ["token" => $token]
        ]);
    }
}