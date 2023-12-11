<?php

use App\Settings\GeneralSetting;

dataset('available_time_check', [
    [1, 0, 1, 1702811876] // 2023-12-17
]);

describe('available times tests', function () {
    /*    it('expects a list of available dates', function () {
            expect(1)->toBe(2);
        });

        it('expects a list of available times', function () {
            expect(1)->toBe(2);
        });

        it('expects no times to return', function () {
            expect(1)->toBe(2);
        });*/
    it('checks that available times return', function ($service, $date, $userid, $startDate) {
        $service = \App\Models\Service::find($service);
        $availableDatetimes = \App\Services\AppointmentService::availableDatetimes($service, $userid, $date);
        expect($availableDatetimes)
            ->toBeArray()
            ->toContainOnlyInstancesOf(\App\Models\Availability::class);
    })->with('available_time_check');

    it('available times are valid', function ($service, $date, $userid, $startDate) {
        $service = \App\Models\Service::find($service);
        $availableDatetimes = \App\Services\AppointmentService::availableDatetimes(
            service: $service,
            date: $date,
            userid: $userid,
            startDate: $startDate);

        /**
         * Dec 18 and Dec 20 unvail
         * Other mondays and weds avail
         */
        $nextMonday = \Carbon\Carbon::parse('December 18 2023'); // unavail
        $nextWednesday = \Carbon\Carbon::parse('December 20 2023'); // unavail
        $next2Monday = \Carbon\Carbon::parse('December 25 2023'); // avail
        $next2Wednesday = \Carbon\Carbon::parse('December 27 2023'); // avail

        /**
         * Not next monday or next weds
         * @var \App\Models\Availability $nextDate
         */
        $nextDate = array_shift($availableDatetimes);
        $nextDate2 = array_shift($availableDatetimes);

        expect($nextDate->date->format('Y-m-d'))
            ->toBe($next2Monday->format('Y-m-d'))
            ->not->toBe($nextMonday->format('Y-m-d'));

        expect($nextDate2->date->format('Y-m-d'))
            ->toBe($next2Wednesday->format('Y-m-d'))
            ->not->toBe($nextWednesday->format('Y-m-d'));

    })->with('available_time_check');

    it('calls appropriate model APIs', function () {
        $service = \App\Models\Service::find(2);
        $availableDates = \App\Services\AppointmentService::availableDatetimes($service, 0, 0);

        $currentDate = \Carbon\Carbon::now();
        $currentDate->add('day', app(GeneralSetting::class)->minimum_day_lookahead);
        foreach ($availableDates as $availableDate) {
            expect($availableDate->date->format('Y-m-d'))->toEqual($currentDate->format('Y-m-d'));
            $currentDate->add('day', 1);
        }
    });

    it('skips user selection if service disallows user selection', function () {
        $this->get('/appointment/service/1')
            ->assertRedirect('/appointment/service/1/1/0');
    });

    it('skips user selection if only one user', function () {
        $this->get('/appointment/service/2')
            ->assertRedirect('/appointment/service/2/1/0');
    });

    it('skips user selection if disallowed - userid 0 if many users assigned', function () {
        $this->get('/appointment/service/3')
            ->assertRedirect('/appointment/service/3/0/0');
    });

});
