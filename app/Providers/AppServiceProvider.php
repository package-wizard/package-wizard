<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\ServiceProvider;
use PackageWizard\Installer\Support\Console;

use function getcwd;
use function realpath;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Composer::class, function () {
            return new Composer(new Filesystem(), getcwd() ?: realpath('.'));
        });

        $this->app->singleton(Console::class, fn () => new Console());
    }
}
