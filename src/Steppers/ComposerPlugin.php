<?php

namespace Helldar\PackageWizard\Steppers;

use Helldar\Support\Facades\Helpers\Str;

final class ComposerPlugin extends BaseStepper
{
    protected $require = [
        'php' => '^8.0',

        'composer-plugin-api' => '^2.0',
    ];

    protected $require_dev = [
        'composer/composer' => '^2.0',
        'mockery/mockery'   => '^1.0',
        'phpunit/phpunit'   => '^9.0',
        'symfony/console'   => '^5.0',
    ];

    public function getExtra(): array
    {
        $namespace = trim($this->getNamespace(), '/\\');

        $namespace = Str::finish($namespace, '\\');

        return ['class' => $namespace . 'Application'];
    }
}
