<?php

namespace Helldar\PackageWizard\Steps;

final class Description extends BaseStep
{
    protected $question = 'Description of package';

    protected function input(): ?string
    {
        return $this->getIO()->ask($this->question());
    }
}
