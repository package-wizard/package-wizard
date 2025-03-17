<?php

declare(strict_types=1);

use LaravelZero\Framework\Testing\TestCase;

uses(TestCase::class)
    ->in('Feature')
    ->afterEach(function () {
        assertFileSnapshots(temp_path());

        expect(['fallback'])->toMatchSnapshot();
    });
