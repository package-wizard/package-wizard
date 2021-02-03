<?php

namespace Helldar\PackageWizard\Steps;

use Helldar\PackageWizard\Constants\Licenses;
use Helldar\Support\Facades\Helpers\Arr;
use Helldar\Support\Facades\Helpers\Is;

final class License extends BaseStep
{
    protected string $question = 'License of package';

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
        return $this->getIO()->select($this->question(), $this->available(), $this->back());
    }

    protected function available(): array
    {
        return Licenses::available();
    }

    protected function back(): int
    {
        return array_search(Licenses::DEFAULT_LICENSE, $this->available());
    }

    protected function stringable(?int $index): ?string
    {
        return Arr::get($this->available(), $index);
    }
}
