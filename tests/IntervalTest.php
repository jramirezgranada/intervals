<?php

use PHPUnit\Framework\TestCase;

final class IntervalTest extends TestCase
{
    protected $client;

    /**
     * Setup BASEURL
     */
    protected function setUp(): void
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => env('BASEURL')
        ]);
    }

    /**
     * Assert Status Response to get all intervals
     */
    public function testGetAllIntervals()
    {
        $response = $this->client->get('/api/intervals');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test to check if end date is greater than start date
     */
    public function testEndDateMustBeGreaterThanStartDate()
    {
        $response = $this->client->post('/api/intervals', [
            'json' => [
                "start_date" => "2018-08-10",
                "end_date" => "2018-08-01",
                "price" => 10
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $this->assertEquals(400, $data["code"]);
        $this->assertStringContainsString("end_date must be greater than start_date", $data["data"]["end_date"][0]);
    }

    /**
     * Test to check if end date is greater than start date
     */
    public function testStartDateEndDateMustBeDates()
    {
        $response = $this->client->post('/api/intervals', [
            'json' => [
                "start_date" => "string",
                "end_date" => "string",
                "price" => 45
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $this->assertEquals(400, $data["code"]);
        $this->assertStringContainsString("end_date must be a valid date", $data["data"]["end_date"][0]);
        $this->assertStringContainsString("start_date must be a valid date", $data["data"]["start_date"][0]);
    }

    /**
     * Price is double, string is not allowed
     */
    public function testPriceMustBeANumber()
    {
        $response = $this->client->post('/api/intervals', [
            'json' => [
                "start_date" => "2018-08-01",
                "end_date" => "2018-08-10",
                "price" => "string"
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $this->assertEquals(400, $data["code"]);
        $this->assertStringContainsString("price must be numeric", $data["data"]["price"][0]);
    }

    /**
     * All fields are required
     */
    public function testAllFieldsAreRequired()
    {
        $response = $this->client->post('/api/intervals', [
            'json' => []
        ]);

        $data = json_decode($response->getBody(), true);

        $this->assertEquals(400, $data["code"]);
        $this->assertStringContainsString("price is a mandantory field", $data["data"]["price"][0]);
        $this->assertStringContainsString("start_date is a mandantory field", $data["data"]["start_date"][0]);
        $this->assertStringContainsString("end_date is a mandantory field", $data["data"]["end_date"][0]);
    }

    /**
     * Check the error when interval id does not exists
     */
    public function testDeleteIntervalIfIdDoesNotExists()
    {
        $response = $this->client->delete('/api/intervals/3000');

        $data = json_decode($response->getBody(), true);

        $this->assertEquals(400, $data["code"]);
        $this->assertStringContainsString("Interval Id does not exists", $data["message"]);
    }
}