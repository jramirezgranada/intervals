<?php

namespace App\Helpers;

use App\Models\Interval;
use App\Validators\FormValidator;

class IntervalHelper
{

    /**
     * Check Dates to merge it or create new ones
     * @param $request
     * @return array
     * @throws \Exception
     */
    public static function checkDates($request)
    {
        $request = $request->bodyToArray();

        $validations = (new FormValidator(
            $request,
            [
                "end_date" => ["date_greater_than|start_date", "required", "date"],
                "start_date" => ["required", "date"],
                "price" => ["required", "number"]
            ]
        ))->validate();

        if (isset($validations["data"])) {
            return $validations;
        }

        $interval = new Interval();
        $interval->start_date = $request["start_date"];
        $interval->end_date = $request["end_date"];
        $interval->price = $request["price"];

        Interval::where('start_date', '>=', $interval->start_date)
            ->where('end_date', '<=', $interval->end_date)
            ->delete();

        list($start_interval, $end_interval) = self::getIntervals(
            $interval->start_date,
            $interval->end_date);
        
        if (!is_null($start_interval) && $start_interval->is($end_interval) && $start_interval->price != $interval->price) {
            $end_interval = new Interval();
            $end_interval->end_date = $start_interval->end_date;
            $end_interval->start_date = $interval->end_date->addDay();
            $end_interval->price = $start_interval->price;

            if ($end_interval->end_date->lt($end_interval->start_date)) {
                $end_interval->delete();
            } else {
                $end_interval->save();
            }

            $start_interval->end_date = $interval->start_date->subDay();

            if ($start_interval->end_date->lt($start_interval->start_date)) {
                $start_interval->delete();
            } else {
                $start_interval->save();
            }

            $interval->save();

            self::mergeDates($interval);

            return self::dataReturn($interval);
        }

        if (is_null($start_interval) && is_null($end_interval)) {

            $interval->save();
            self::mergeDates($interval);

            return self::dataReturn($interval);
        }

        if (!is_null($start_interval) && !is_null($end_interval)) {
            if ($start_interval->price == $interval->price && $interval->price == $end_interval->price) {
                if ($start_interval != $end_interval) {
                    $start_interval->end_date = $end_interval->end_date;
                    $start_interval->save();
                    $end_interval->delete();
                }
            }
        }
        if (!is_null($start_interval)) {
            if ($start_interval->price == $interval->price) {
                if ($interval->end_date->gte($start_interval->end_date)) {
                    $start_interval->end_date = $interval->end_date;
                    $start_interval->save();
                }
            } else {
                $start_interval->end_date = $interval->start_date->subDay();
                $start_interval->save();
            }
        }
        if (!is_null($end_interval)) {

            if ($end_interval->price == $interval->price) {
                if ($interval->start_date->lte($end_interval->start_date)) {
                    $end_interval->start_date = $interval->start_date;
                    $end_interval->save();
                }
            } else {
                $end_interval->start_date = $interval->end_date->addDay();
                $end_interval->save();
            }
        }

        self::mergeDates($interval);

        return self::dataReturn($interval);
    }

    /**
     * Function to merge dates
     * @param $interval
     */
    private static function mergeDates($interval)
    {
        list($start_interval, $end_interval) = self::getIntervals(
            $interval->start_date,
            $interval->end_date,
            $interval->price,
            true);

        if (!is_null($start_interval)) {
            $interval->start_date = $start_interval->start_date;
            $start_interval->delete();
        }
        if (!is_null($end_interval)) {
            $interval->end_date = $end_interval->end_date;
            $end_interval->delete();
        }
        $interval->save();
    }

    /**
     * Delete an interval by id
     * @param $request
     * @return array
     */
    public static function delete($request)
    {
        $request = $request->params();

        $interval = Interval::find($request["id"]);

        if (!$interval) {
            return self::dataReturn([], "Interval Id does not exists", 400, 'error');
        }

        Interval::destroy($request["id"]);

        return self::dataReturn([], "Interval was deleted");
    }

    /**
     * Generic Format to return data
     * @param $data
     * @param string $message
     * @param int $code
     * @param string $status
     * @return array
     */
    private static function dataReturn($data, $message = "Interval Added", $code = 200, $status = "Ok")
    {
        return [
            "status" => $status,
            "message" => $message,
            "code" => $code,
            "data" => $data
        ];
    }

    /**
     * Get intervals dates
     * @param $date_start
     * @param $date_end
     * @param null $price
     * @param bool $isMerge
     * @return array
     */
    private static function getIntervals($startDate, $endDate, $price = null, $isMerge = false)
    {
        $start_interval = Interval::where('start_date', '<=', $isMerge ? $startDate->subDay() : $startDate)
            ->where('end_date', '>=', $isMerge ? $startDate->subDay() : $startDate);

        if (!is_null($price))
            $start_interval = $start_interval->where('price', $price);

        $start_interval = $start_interval->first();

        $end_interval = Interval::where('start_date', '<=', $isMerge ? $endDate->addDay() : $endDate)
            ->where('end_date', '>=', $isMerge ? $endDate->addDay() : $endDate);

        if (!is_null($price))
            $end_interval = $end_interval->where('price', $price);

        $end_interval = $end_interval->first();

        return [
            $start_interval,
            $end_interval
        ];
    }

}