<?php

namespace App\Controllers;

use App\Factories\IntervalFactory;

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
        return $response->json(IntervalFactory::all());
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
        return $response->json(IntervalFactory::create($request));
    }

    /**
     * Delete a specific interval
     * @param $request
     * @param $response
     * @return mixed
     */
    public function delete($request, $response)
    {
        return $response->json(IntervalFactory::delete($request));
    }

    /**
     * Update a specific interval
     * @param $request
     * @param $response
     * @return mixed
     */
    public function update($request, $response)
    {
        return $response->json(IntervalFactory::update($request));
    }
}