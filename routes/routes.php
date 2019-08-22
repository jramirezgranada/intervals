<?php

$router = new \Klein\Klein();

/*
 * API Routes
 */
$router->with('/api/', function () use ($router) {
    $router->get('intervals', 'App\Controllers\IntervalController@index');
    $router->post('intervals', 'App\Controllers\IntervalController@store');
    $router->delete('intervals/[:id]', 'App\Controllers\IntervalController@delete');
    $router->patch('intervals/[:id]', 'App\Controllers\IntervalController@update');
    $router->get('truncate-intervals', 'App\Controllers\IntervalController@truncate');
});

/*
 * Web Routes
 */

$router->get('/', function ($request, $response, $service, $app) {
    $service->render('views/index.phtml');
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
