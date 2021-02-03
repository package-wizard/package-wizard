<?php

namespace Helldar\PackageWizard\Steps;

use Helldar\Support\Facades\Helpers\Http;
use Helldar\Support\Facades\Helpers\HttpBuilder;

final class RepositoryUrl extends BaseStep
{
    protected string $question = 'Repository URL of package';

    protected function input(): ?string
    {
        return $this->getIO()->askAndValidate($this->question(), function ($value) {
            if (empty($value)) {
                return null;
            }

            if (! Http::isUrl($value)) {
                $this->warning('This is not a valid URL.');

                return null;
            }

            if (! Http::exists($value)) {
                $this->warning('This URL does not exist.');

                return null;
            }

            return HttpBuilder::parse($value)->compile();
        });
    }
}
