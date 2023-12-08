<?php
declare(strict_types=1);
namespace App\Models;

use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $question
 * @property string $hint
 * @property QuestionType $type
 * @property array $type_meta
 * @property int $order
 * @property bool $required
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
