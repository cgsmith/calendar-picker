<?php
/**
 * ArchitectureTest
 *
 * These tests are meant to enforce architectural rules for the entire application.
 */

// Ensure that debugging functions are not used in production.
test('Debugging functions are not used in production')
    ->expect(['dd', 'dump', 'var_dump', 'print_r', 'error_log', 'phpinfo', 'xdebug_'])
    ->not->toBeUsed();

test('Abstracts are abstract classes')
    ->expect('App\Abstracts')
    ->classes()
    ->toBeAbstract();

test('Associators')
    ->expect('App\Associators')
    ->classes()
    ->toExtend('App\Abstracts\Associator')
    ->toHaveSuffix('Associator')
    ->toImplement('App\Contracts\Associable');

test('app uses strict types')
    ->expect('App')
    ->toUseStrictTypes();

test('Contracts are interfaces')
    ->expect('App\Contracts')
    ->classes()
    ->toBeInterfaces();

test('Fetchers')
    ->expect('App\Fetchers')
    ->classes()
    ->toHaveSuffix('Fetcher')
    ->toImplement('App\Contracts\Fetchable');

test('Pipelines')
    ->expect('App\Pipelines')
    ->classes()
    ->toHaveSuffix('Pipeline')
    ->toExtend('Illuminate\Pipeline\Pipeline');

test('Transformers')
    ->expect('App\Transformers')
    ->classes()
    ->toExtend('App\Abstracts\Transformer')
    ->toHaveSuffix('Transformer')
    ->toImplement('App\Contracts\Transformable');

test('ValueObjects')
    ->expect('App\ValueObjects')
    ->classes()
    ->toBeFinal()
    ->toExtendNothing();
