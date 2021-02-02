<?php

namespace Helldar\PackageWizard\Steppers;

final class LaravelPackage extends BaseStepper
{
    protected $require = [
        'php' => '^8.0',
    ];

    protected $require_dev = [
        'mockery/mockery'     => '^1.0',
        'orchestra/testbench' => '^6.0',
        'phpunit/phpunit'     => '^9.0',
    ];
}
