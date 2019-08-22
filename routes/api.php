<?php

$router = new \Klein\Klein();
$router->with('/api/', function () use ($router) {
    $router->get('intervals', 'App\Controllers\IntervalController@index');
    $router->post('intervals', 'App\Controllers\IntervalController@store');
    $router->delete('intervals/[:id]', 'App\Controllers\IntervalController@delete');
    $router->patch('intervals/[:id]', 'App\Controllers\IntervalController@update');
});

$router->onHttpError(function ($code, $router) {
    return $router->response()->json(
        [
            "status" => "error",
            "code" => $code,
            "message" => "There is an error, please check the error code"
        ]
    );
});

$router->dispatch();
