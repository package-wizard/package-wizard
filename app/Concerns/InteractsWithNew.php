<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Concerns;

use PackageWizard\Installer\Services\HttpService;

/** @mixin \PackageWizard\Installer\Commands\NewCommand */
trait InteractsWithNew
{
    protected function repositoryPath(HttpService $http): string
    {
        if ($this->option('local')) {
            return $this->argument('name');
        }

        return $http->download(
            $this->argument('name'),
            $this->argument('path'),
            (bool) $this->option('dev')
        );
    }
}
