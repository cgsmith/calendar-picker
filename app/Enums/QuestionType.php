<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum QuestionType: string implements HasLabel
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Select = 'select';
    case Checkbox = 'checkbox';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Text => __('Text'),
            self::Textarea => __('Text Area'),
            self::Select => __('Select'),
            self::Checkbox => __('Checkbox'),
        };
    }

}
