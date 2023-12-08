<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string question
 * @property string hint
 * @property string type
 * @property array type_meta
 * @property bool required
 */
class ServiceQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'sort'
    ];

    protected $casts = [
        'type_meta' => 'array'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
