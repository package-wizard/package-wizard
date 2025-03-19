<?php

declare(strict_types=1);

use Illuminate\Translation\TranslationServiceProvider;
use PackageWizard\Installer\Providers\AppServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;

return [
    AppServiceProvider::class,
    LaravelDataServiceProvider::class,
    TranslationServiceProvider::class,
];
