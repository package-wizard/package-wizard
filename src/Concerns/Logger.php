<?php

namespace Helldar\PackageWizard\Concerns;

use Helldar\PackageWizard\Facades\Logger as Service;

trait Logger
{
    protected function setLogger(): void
    {
        Service::set($this->getIO());
    }

    protected function log(...$values): void
    {
        Service::write($values);
    }
}
