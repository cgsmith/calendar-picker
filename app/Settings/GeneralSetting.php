<?php
declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSetting extends Settings
{
    public int $minimum_day_lookahead;
    public int $maximum_day_lookahead;

    public static function group(): string
    {
        return 'general';
    }
}
