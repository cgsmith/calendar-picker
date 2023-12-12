<?php
declare(strict_types=1);
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum QuestionType: string implements HasLabel, HasColor
{
    case text = 'text';
    case textarea = 'textarea';
    case select = 'select';
    case checkbox = 'checkbox';

    case radio = 'radio';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::text => __('Text'),
            self::textarea => __('Text Area'),
            self::select => __('Select'),
            self::checkbox => __('Checkbox'),
            self::radio => __('Radio'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::text => 'success',
            self::textarea => 'secondary',
            self::select => 'danger',
            self::checkbox => 'warning',
            self::radio => 'info',
        };
    }
}
