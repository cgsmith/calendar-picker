<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $parent_service_question_id if set this is the child of the parent when the parent item is true
 * @property string $key
 * @property string $question
 * @property string|null $hint
 * @property QuestionType $type
 * @property array<string>|null $type_meta
 * @property int $order
 * @property bool $required
 */
class ServiceQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'sort',
    ];

    protected $casts = [
        'type_meta' => 'array',
        'type' => QuestionType::class,
    ];

    protected static function booted()
    {
        // Set type_meta to null if updating the record and question type does not need meta data
        static::updating(function (ServiceQuestion $serviceQuestion) {
            if (in_array($serviceQuestion->type, [QuestionType::text, QuestionType::textarea])) {
                $serviceQuestion->type_meta = null;
            }
        });
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ServiceQuestion::class, 'parent_service_question_id');
    }
}
