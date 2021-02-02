<?php

namespace Helldar\PackageWizard\Exceptions;

final class UnknownStepException extends \Exception
{
    public function __construct(string $key)
    {
        parent::__construct('Unknown installer step: ' . $key);
    }
}
