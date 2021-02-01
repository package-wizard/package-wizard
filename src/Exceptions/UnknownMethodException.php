<?php

namespace Helldar\PackageWizard\Exceptions;

use Exception;

final class UnknownMethodException extends Exception
{
    public function __construct(string $method)
    {
        parent::__construct("The {$method} method was not found.");
    }
}
