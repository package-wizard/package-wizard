<?php

namespace Helldar\PackageWizard\Steps;

final class Boolean extends BaseStep
{
    protected $default = true;

    public function back(bool $default = true): self
    {
        $this->default = $default;

        return $this;
    }

    protected function input(): bool
    {
        return $this->io->askConfirmation($this->question, $this->default);
    }
}
