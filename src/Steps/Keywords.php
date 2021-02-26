<?php

namespace Helldar\PackageWizard\Steps;

final class Keywords extends BaseStep
{
    protected $question = 'Keyword of package';

    protected $ask_many = true;

    protected function input(): ?string
    {
        return $this->getIO()->askAndValidate($this->question(), static function ($value) {
            return trim($value);
        });
    }

    protected function post($result): array
    {
        sort($result);

        return array_values(array_unique(array_filter($result)));
    }
}
