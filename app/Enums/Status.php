<?php
declare(strict_types=1);
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasLabel, HasColor
{
    case Past = 'past';
    case Today = 'today';
    case Upcoming = 'upcoming';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Past => __('Past'),
            self::Today => __('Today'),
            self::Upcoming => __('Upcoming'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Past => 'gray',
            self::Today => 'success',
            self::Upcoming => 'warning',
        };
    }
}
