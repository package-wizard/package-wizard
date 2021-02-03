<?php

namespace Helldar\PackageWizard\Steppers;

final class SymfonyBundle extends BaseStepper
{
    protected string $type = 'symfony-bundle';

    protected array $autoload_dev = [];

    protected string $autoload_path = '';
}
