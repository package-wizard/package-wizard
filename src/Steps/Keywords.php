<?php

namespace Helldar\PackageWizard\Steps;

final class Keywords extends BaseStep
{
    protected string $question = 'Keyword of package';

    protected bool $ask_many = true;

    protected function input(): ?string
    {
        return $this->getIO()->askAndValidate($this->question(), static fn($value) => trim($value));
    }

    protected function post($result): array
    {
        sort($result);

        return array_values(array_unique(array_filter($result)));
    }
}
