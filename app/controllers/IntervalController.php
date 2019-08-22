<?php

namespace App\Controllers;

use App\Helpers\IntervalHelper;
use App\Models\Interval;

class IntervalController
{
    /**
     * Get all intervals
     * @param $request
     * @param $response
     * @return mixed
     */
    public function index($request, $response)
    {
        return $response->json(Interval::orderBy('start_date', 'ASC')->get());
    }

    /**
     * Store interval Information
     * @param $request
     * @param $response
     * @param $service
     * @return mixed
     */
    public function store($request, $response, $service)
    {
        return $response->json(IntervalHelper::checkDates($request));
    }

    /**
     * Delete a specific interval
     * @param $request
     * @param $response
     * @return mixed
     */
    public function delete($request, $response)
    {
        return $response->json(IntervalHelper::delete($request));
    }

    /**
     * Update a specific interval
     * @param $request
     * @param $response
     * @return mixed
     */
    public function update($request, $response)
    {
        //return $response->json(IntervalFactory::update($request));
    }

    /**
     * Feature to truncate intervals table
     * @param $request
     * @param $response
     * @return mixed
     */
    public function truncate($request, $response)
    {
        Interval::truncate();

        return $response->json([
            "status" => "Ok",
            "message" => "Interval Table was truncated",
            "code" => 200
        ]);
    }
}