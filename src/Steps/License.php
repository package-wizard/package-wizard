<?php

namespace Helldar\PackageWizard\Steps;

use Helldar\PackageWizard\Constants\Licenses;

final class License extends BaseStep
{
    protected string $question = 'License of package';

    protected function input(): ?string
    {
        return $this->getIO()->select($this->question(), Licenses::available(), Licenses::DEFAULT_LICENSE);
    }
}
