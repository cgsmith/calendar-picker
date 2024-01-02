<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class() extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.minimum_day_lookahead', 7);
        $this->migrator->add('general.maximum_day_lookahead', 60);
    }
};
