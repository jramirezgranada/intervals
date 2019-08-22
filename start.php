<?php

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

use App\Models\Database;

new Database();

require 'routes/routes.php';
