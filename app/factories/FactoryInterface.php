<?php


namespace App\Factories;


interface FactoryInterface
{
    public static function all();

    public static function get($req);

    public static function create($req);

    public static function delete($req);

    public static function update($req);
}