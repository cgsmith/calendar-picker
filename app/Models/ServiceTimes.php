<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use App\Enums\ServiceTimeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTimes extends Model
{
    use HasFactory;

    protected $casts = [
        'day_of_week' => DayOfWeek::class,
        'type' => ServiceTimeType::class,
    ];
}
