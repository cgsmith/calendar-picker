<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $name
 * @property string $description
 * @property int $duration
 * @property bool $all_day
 * @property int $minimum_cancel_hours
 * @property bool $allow_user_selection
 * @property bool $active
 */
class Service extends Model
{
    use HasFactory;

    protected $casts = [
        'active' => 'boolean',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(ServiceQuestion::class)
            ->orderByRaw('COALESCE(parent_service_question_id, id)')
            ->orderByRaw('CASE WHEN parent_service_question_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('order');
    }

    public function appointment(): HasOne
    {
        return $this->hasOne(Appointment::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function times(): HasMany
    {
        return $this->hasMany(ServiceTimes::class)
            ->orderBy('day_of_week', 'asc')
            ->orderBy('hour', 'asc')
            ->orderBy('minute', 'asc');
    }
}
