<?php

namespace Helldar\PackageWizard\Steps;

use Helldar\PackageWizard\Facades\Licenses;
use Helldar\Support\Facades\Helpers\Arr;
use Helldar\Support\Facades\Helpers\Is;

final class License extends BaseStep
{
    protected $question = 'License of package';

    protected $cached = [];

    protected function input(): ?string
    {
        $index = $this->ask();

        if (Is::doesntEmpty($index)) {
            return $this->stringable($index);
        }

        return null;
    }

    protected function ask(): ?int
    {
        return $this->getIO()->select($this->question(), $this->getCachedValues(), $this->back());
    }

    protected function available(): array
    {
        if (empty($this->cached)) {
            foreach (Licenses::available() as $name) {
                $this->cached[$name] = str_replace('_', ' ', $name);
            }
        }

        return $this->cached;
    }

    protected function getCachedValues(): array
    {
        return array_values($this->available());
    }

    protected function getCachedKeys(): array
    {
        return array_keys($this->available());
    }

    protected function back(): int
    {
        return array_search(Licenses::getDefault(), $this->getCachedValues());
    }

    protected function stringable(?int $index): ?string
    {
        return Arr::get($this->getCachedKeys(), $index);
    }
}
