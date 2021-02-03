<?php

namespace Helldar\PackageWizard\Steps;

final class Description extends BaseStep
{
    protected string $question = 'Description of package';

    protected function input(): ?string
    {
        return $this->getIO()->ask($this->question());
    }
}
