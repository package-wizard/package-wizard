<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;

uses(TestCase::class)
    ->in('Feature')
    ->beforeEach(static function () {
        Http::preventStrayRequests();
    });
