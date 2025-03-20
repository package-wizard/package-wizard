<?php

declare(strict_types=1);

use LaravelZero\Framework\Testing\TestCase;

uses(TestCase::class)
    ->in('Feature')
    ->afterEach(function () {
        if (env('GITHUB_ACTIONS')) {
            return;
        }

        assertFileSnapshots(temp_path());

        expect(['fallback'])->toMatchSnapshot();
    });
