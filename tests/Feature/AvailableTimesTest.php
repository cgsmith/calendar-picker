<?php
dataset('days_of_the_week', [
    'Sunday',
    'Monday',
]);

dataset('service', [
    ['']
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

    it('skips user selection if only one user', function () {
        $this->get('/appointment/service/1')
            ->assertRedirect('/appointment/service/1/1/0');
    });

});
