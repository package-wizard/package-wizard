<?php

namespace Helldar\PackageWizard\Steps;

use Helldar\Support\Facades\Helpers\Http;

final class RepositoryUrl extends BaseStep
{
    protected string $question = 'Repository URL of package: ';

    protected function input(): ?string
    {
        $value = $this->getIO()->ask($this->question);

        if (Http::isUrl($value)) {
            return $value;
        }

        $this->warning('An invalid URL was specified.');

        return $this->input();
    }
}
