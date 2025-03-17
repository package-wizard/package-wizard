<?php

declare(strict_types=1);

use LaravelZero\Framework\Testing\TestCase;

uses(TestCase::class)
    ->in('Feature')
    ->afterEach(
        fn () => assertFileSnapshots(temp_path())
    );
