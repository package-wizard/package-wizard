<?php

namespace Helldar\PackageWizard\Steps;

final class Description extends BaseStep
{
    protected string $question = 'Description of package: ';

    protected function input()
    {
        return $this->getIO()->askAndValidate($this->question, static fn($value) => ! empty($value));
    }
}
