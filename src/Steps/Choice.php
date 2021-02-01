<?php

namespace Helldar\PackageWizard\Steps;

final class Choice extends BaseStep
{
    protected $choices = [];

    protected $default;

    public function choices(array $choices): self
    {
        $this->choices = $choices;

        return $this;
    }

    public function back($default): self
    {
        $this->default = $default;

        return $this;
    }

    protected function input()
    {
        return $this->io->select($this->question, $this->choices, $this->default);
    }
}
