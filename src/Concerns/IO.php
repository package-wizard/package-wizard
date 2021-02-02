<?php

namespace Helldar\PackageWizard\Concerns;

use Composer\IO\IOInterface;

trait IO
{
    /** @var \Composer\IO\IOInterface */
    protected IOInterface $io;

    public function getIO(): IOInterface
    {
        return $this->io;
    }
}
