<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $description
 * @property string $start
 * @property string $end
 * @property string $status
 * @property int $contact_id
 * @property int $user_id
 * @property int $service_id
 * @property string $created_at
 * @property string $updated_at
 */
class Appointment extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => Status::class,
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
