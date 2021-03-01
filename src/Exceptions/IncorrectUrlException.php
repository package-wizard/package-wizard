<?php

namespace Helldar\PackageWizard\Exceptions;

use Exception;

final class IncorrectUrlException extends Exception
{
    public function __construct(string $key)
    {
        parent::__construct('Unknown installer step: ' . $key);
    }
}
