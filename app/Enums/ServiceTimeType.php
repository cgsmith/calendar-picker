<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ServiceTimeType: int implements HasLabel
{
    case Start = 0;
    case End = 1;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Start => __('Start'),
            self::End => __('End'),
        };
    }
}
