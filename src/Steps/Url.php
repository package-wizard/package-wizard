<?php

namespace Helldar\PackageWizard\Steps;

use Helldar\Support\Facades\Helpers\Http;

final class Url extends BaseStep
{
    protected function input(): ?string
    {
        $value = $this->io->ask($this->question);

        if (Http::isUrl($value)) {
            return $value;
        }

        $this->output->warning('An invalid URL was specified.');

        return $this->input();
    }
}
