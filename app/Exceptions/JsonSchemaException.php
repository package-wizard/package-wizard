<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Exceptions;

class JsonSchemaException extends WizardLogicException
{
    public function __construct(array $errors)
    {
        parent::__construct('JSON does not validate');

        $this->setErrors($errors);
    }
}
