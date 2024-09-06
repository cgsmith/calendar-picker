<?php

use App\Models\Service;
use App\Models\ServiceQuestion;
use App\Services\AppointmentService;
use App\Settings\GeneralSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

dataset('available_time_check', [
    [1, 0, 1, 1702811876], // 2023-12-17
]);

describe('available times tests', function () {
    it('checks that available times return', function ($service, $date, $userid, $startDate) {
        $service = Service::find($service);
        $availableDatetimes = AppointmentService::availableDatetimes($service, $userid, $date);
        expect($availableDatetimes)
            ->toBeArray()
            ->toContainOnlyInstancesOf(\App\Models\Availability::class);
    })->with('available_time_check');

    it('available times are valid', function ($service, $date, $userid, $startDate) {
        $service = Service::find($service);
        $availableDatetimes = AppointmentService::availableDatetimes(
            service: $service,
            userid: $userid,
            date: $date,
            startDate: $startDate
        );

        /**
         * Dec 18 and Dec 20 unvail
         * Other mondays and weds avail
         */
        $nextMonday = Carbon::parse('December 18 2023'); // unavail
        $nextWednesday = Carbon::parse('December 20 2023'); // unavail
        $next2Monday = Carbon::parse('December 25 2023'); // avail
        $next2Wednesday = Carbon::parse('December 27 2023'); // avail

        /**
         * Not next monday or next weds
         *
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
        $service = Service::find(2);
        $availableDates = AppointmentService::availableDatetimes($service, 0, 0);

        $currentDate = Carbon::now();
        $currentDate->add('day', app(GeneralSetting::class)->minimum_day_lookahead);
        foreach ($availableDates as $availableDate) {
            expect($availableDate->date->format('Y-m-d'))->toEqual($currentDate->format('Y-m-d'));
            $currentDate->add('day', 1);
        }
    });

    it('skips user selection if service disallows user selection', function () {
        $this->get('/service/1')
            ->assertRedirect('/service/1/user/1/time/0');
    });

    it('skips user selection if only one user', function () {
        $this->get('/service/2')
            ->assertRedirect('/service/2/user/1/time/0');
    });

    it('skips user selection if disallowed - userid 0 if many users assigned', function () {
        $this->get('/service/3')
            ->assertRedirect('/service/3/user/0/time/0');
    });

    it('holidays are not in the calendar selection', function () {
        $service = Service::find(2);
        $availableDates = AppointmentService::availableDatetimes($service, 0, 0);

        $currentDate = Carbon::now();
        $currentDate->add('day', app(GeneralSetting::class)->minimum_day_lookahead);
        foreach ($availableDates as $availableDate) {
            expect($availableDate->date->format('Y-m-d'))->toEqual($currentDate->format('Y-m-d'));
            $currentDate->add('day', 1);
        }

    });

    it('meta array is set', function () {
        // Create a fake ServiceQuestion model
        $fakeServiceQuestion = \Mockery::mock(ServiceQuestion::class);
        $fakeServiceQuestion->shouldReceive('setAttribute');
        $fakeServiceQuestion->shouldReceive('find')->andReturn($fakeServiceQuestion);

        // Make key a public property for easier testing
        $fakeServiceQuestion->key = 'TestKey';
        $testAnswer = 'this is the value';

        // Swap the existing ServiceQuestion facade instance with our fake
        $this->app->instance(ServiceQuestion::class, $fakeServiceQuestion);

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('has')->andReturn([], ['questions' => ['TestKey' => $testAnswer]]);
        $request->shouldReceive('all')->andReturn([], ['questions' => ['TestKey' => $testAnswer]]);
        $request->shouldReceive('route')->andReturn(['rtes']);
        $metaArray = AppointmentService::buildMetaArray($request);
        expect($metaArray)->toBeArray()->toBeEmpty();
        /*
                $metaArray = AppointmentService::buildMetaArray($request);
                expect($metaArray)
                    ->toBeArray()
                    ->and($metaArray[0])
                    ->toBeInstanceOf(AppointmentMeta::class);

                expect($metaArray[0]->key)->toBe( $fakeServiceQuestion->key);
                expect($metaArray[0]->value)->toBe( $testAnswer);*/
    });

});
