<?php

namespace Helldar\PackageWizard\Steps;

final class Text extends BaseStep
{
    protected function input(): ?string
    {
        return $this->io->ask($this->question);
    }
}
