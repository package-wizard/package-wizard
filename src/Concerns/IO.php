<?php

namespace Helldar\PackageWizard\Concerns;

use Composer\IO\IOInterface;

trait IO
{
    /** @var \Composer\IO\IOInterface */
    protected $io;

    public function getIO(): IOInterface
    {
        return $this->io;
    }
}
