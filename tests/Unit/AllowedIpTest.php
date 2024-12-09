<?php

// Ensure that debugging functions are not used in production.

use App\Http\Middleware\AllowedIps;
use Illuminate\Support\Facades\Config;

covers(AllowedIps::class);
describe('ensure .env is string for allow ip middleware', function () {
    it('check complete .env does not crash app', function () {
        Config::shouldReceive('get')
            ->once()
            ->with('app.allowed_ips')
            ->andReturn('127.0.0.1');

        $allowIpsMiddleware = new AllowedIps;
        expect($allowIpsMiddleware->ips)
            ->toBeArray();
    });

    it('check null .env does not crash app', function () {
        Config::shouldReceive('get')
            ->once()
            ->with('app.allowed_ips')
            ->andReturn('');

        $allowIpsMiddleware = new AllowedIps;
        expect($allowIpsMiddleware->ips)
            ->toBeArray()
            ->toBeEmpty();
    });
});
