<?php

namespace Helldar\PackageWizard\Steppers;

final class Native extends BaseStepper
{
    protected array $require = [
        'php' => '^8.0',
    ];

    protected array $require_dev = [
        'mockery/mockery' => '^1.0',
        'phpunit/phpunit' => '^9.0',
    ];
}
