<?php
dataset('days_of_the_week', [
    'Sunday',
    'Monday',
]);

dataset('available_time_check', [
    [1, 0, 1]
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
    it('checks that available times return', function ($service, $date, $userid) {
        $service = \App\Models\Service::find($service);
        $availableDatetimes = \App\Services\AppointmentService::availableDatetimes($service, $date, $userid);
        expect($availableDatetimes)
            ->toBeArray()
            ->toContainOnlyInstancesOf(\App\Models\Availability::class);
    })->with('available_time_check');

    it('available times are valid', function ($service, $date, $userid) {
        $service = \App\Models\Service::find($service);
        $availableDatetimes = \App\Services\AppointmentService::availableDatetimes($service, $date, $userid);
        $nextMonday = \Carbon\Carbon::parse('next monday'); // unavail
        $nextWednesday = \Carbon\Carbon::parse('next wednesday'); // avail
        $next2Monday = \Carbon\Carbon::parse('+2 monday'); // unavail
        $next2Wednesday = \Carbon\Carbon::parse('+2 wednesday'); // avail

        /**
         * Not next monday or next weds
         * @var \App\Models\Availability $nextDate
         */
        $nextDate = array_shift($availableDatetimes);
        $nextDate2 = array_shift($availableDatetimes);

        expect($nextDate->date->format('Y-m-d'))
            ->toEqual($next2Monday->format('Y-m-d'))
            ->not->toEqual($nextMonday->format('Y-m-d'));
        expect($nextDate2->date->format('Y-m-d'))
            ->toEqual($next2Wednesday->format('Y-m-d'))
            ->not->toEqual($nextWednesday->format('Y-m-d'));

    })->with('available_time_check');

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
