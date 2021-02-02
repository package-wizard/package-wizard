<?php

namespace Helldar\PackageWizard\Steppers;

final class SymfonyBundle extends BaseStepper
{
    protected $type = 'symfony-bundle';

    protected $require = [
        'php' => '^8.0',
    ];

    protected $autoload_dev = [];

    protected $autoload_path = '';
}
