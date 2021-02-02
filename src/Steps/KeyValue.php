<?php

namespace Helldar\PackageWizard\Steps;

final class KeyValue extends BaseStep
{
    protected $ask_many = true;

    protected $values;

    public function values(array $values): self
    {
        $this->values = $values;

        return $this;
    }

    protected function input(): array
    {
        $this->output->info($this->question);

        $items = [];

        foreach ($this->values as $value) {
            $items[] = $this->io->ask("Input {$value}:");
        }

        return $items;
    }
}
