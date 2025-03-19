<?php

declare(strict_types=1);

use Illuminate\Translation\TranslationServiceProvider;
use LaravelLang\Config\ServiceProvider as LocalesConfigServiceProvider;
use LaravelLang\Locales\ServiceProvider as LocalesServiceProvider;
use PackageWizard\Installer\Providers\AppServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;

return [
    AppServiceProvider::class,
    LaravelDataServiceProvider::class,
    TranslationServiceProvider::class,
    LocalesConfigServiceProvider::class,
    LocalesServiceProvider::class,
];
