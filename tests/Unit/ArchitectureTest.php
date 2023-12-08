<?php

test('expect only enums')
    ->expect('App\Enums')
    ->toBeEnums();

test('expect only classes')
    ->expect('App\Models')
    ->toBeClasses();

test('app uses strict types')
    ->expect('App')
    ->toUseStrictTypes();
