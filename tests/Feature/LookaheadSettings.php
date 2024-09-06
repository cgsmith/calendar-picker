<?php

use App\Models\Service;
use App\Services\AppointmentService;
use App\Settings\GeneralSetting;

dataset('lookahead_settings', [
    [1, 15], // min days, max days
    [4, 30],
    [15, 45],
    [60, 100],
]);

test('check the lookahead setting works', function ($minDays, $maxDays) {
    // get old lookahead method
    $oldMinLookahead = app(GeneralSetting::class)->minimum_day_lookahead;
    $oldMaxLookahead = app(GeneralSetting::class)->maximum_day_lookahead;
    $settings = app(GeneralSetting::class);

    // test for 15 days ahead
    $settings->minimum_day_lookahead = $minDays;
    $settings->maximum_day_lookahead = $maxDays;
    $settings->save();
    $immutable = \Carbon\CarbonImmutable::now();
    $minimumMutableDate = $immutable->add($minDays, 'day');
    $maximumMutableDate = $immutable->add($minDays + $maxDays, 'day');

    // second service
    $service = Service::find(2);
    $availableDatetimes = AppointmentService::availableDatetimes($service, 1, 0);

    $firstTimeAvail = array_shift($availableDatetimes);
    $lastTimeAvail = array_pop($availableDatetimes);

    expect($minimumMutableDate->format('Y-m-d'))->toBe($firstTimeAvail->date->format('Y-m-d'))
        ->and($maximumMutableDate->format('Y-m-d'))->toBe($lastTimeAvail->date->format('Y-m-d'));

    $settings->minimum_day_lookahead = $oldMinLookahead;
    $settings->maximum_day_lookahead = $oldMaxLookahead;
    $settings->save();
})->with('lookahead_settings');
