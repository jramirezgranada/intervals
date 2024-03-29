<?php

namespace App\Models;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    /**
     * Database constructor.
     */
    public function __construct()
    {
        $capsule = new Capsule();

        $capsule->addConnection([
            'driver' => getenv("DBDRIVER"),
            'host' => getenv("DBHOST"),
            'database' => getenv("DBNAME"),
            'username' => getenv("DBUSER"),
            'password' => getenv("DBPASS"),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $capsule->bootEloquent();
    }
}