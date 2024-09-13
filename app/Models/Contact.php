<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use HasFactory, Prunable;

    protected $fillable = ['email', 'name', 'phone'];

    public function appointment(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function prunable(): Builder
    {
        return static::doesntHave('appointment');
    }
}
