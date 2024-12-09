<?php

declare(strict_types=1);

namespace App\Enums;

class Locale
{
    const EN = 'en';

    const DE = 'de';

    /**
     * @return array<string, string>
     */
    public static function getOptions(): array
    {
        return [
            self::EN => __('English'),
            self::DE => __('German'),
        ];
    }
}
