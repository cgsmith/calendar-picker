<?php
declare(strict_types=1);
namespace App\Models;

use App\Enums\DayOfWeek;
use App\Enums\ServiceTimeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $service_id
 * @property DayOfWeek $day_of_week (0-6 with 0 starting at Sunday)
 * @property ServiceTimeType $type
 * @property int $hour
 * @property int $minute
 * @property string $created_at
 * @property string $updated_at
 */
class ServiceTimes extends Model
{
    use HasFactory;

    protected $casts = [
        'day_of_week' => DayOfWeek::class,
        'type' => ServiceTimeType::class,
    ];
}
