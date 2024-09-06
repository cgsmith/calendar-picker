<?php

// Ensure that debugging functions are not used in production.

use App\Http\Middleware\BlockIp;

describe('ensure .env is string for block ip middleware', function () {
    it('check complete .env does not crash app', function () {
        $_ENV['ALLOWED_IPS'] = 'string is set';
        $blockIp = new BlockIp();
        expect($blockIp->allowIps)
            ->toBeArray();
    });

    it('check null .env does not crash app', function () {
        unset($_ENV['ALLOWED_IPS']);
        $blockIp = new BlockIp();
        expect($blockIp->allowIps)
            ->toBeArray();
    });
});
