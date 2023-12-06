<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $casts = [
        'active' => 'boolean'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(ServiceQuestion::class);
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
