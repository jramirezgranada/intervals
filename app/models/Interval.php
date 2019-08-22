<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interval extends Model
{
    protected $table = 'intervals';

    protected $casts = [
        "start_date" => "date",
        "end_date" => "date"
    ];
}