<?php
declare(strict_types=1);
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum QuestionType: string implements HasLabel, HasColor
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Select = 'select';
    case Checkbox = 'checkbox';

    case Radio = 'radio';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Text => __('Text'),
            self::Textarea => __('Text Area'),
            self::Select => __('Select'),
            self::Checkbox => __('Checkbox'),
            self::Radio => __('Radio'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Text => 'success',
            self::Textarea => 'secondary',
            self::Select => 'danger',
            self::Checkbox => 'warning',
            self::Radio => 'info',
        };
    }
}
