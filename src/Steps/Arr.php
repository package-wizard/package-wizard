<?php

namespace Helldar\PackageWizard\Steps;

final class Arr extends BaseStep
{
    protected $ask_many = true;

    protected function input()
    {
        return $this->io->ask($this->question);
    }
}
