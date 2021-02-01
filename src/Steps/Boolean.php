<?php

namespace Helldar\PackageWizard\Steps;

final class Boolean extends BaseStep
{
    protected function input()
    {
        return $this->io->askConfirmation($this->question);
    }
}
