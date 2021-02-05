<?php

namespace Helldar\PackageWizard\Steppers;

use Helldar\PackageWizard\Structures\SymfonyBundle as Structure;

final class SymfonyBundle extends BaseStepper
{
    protected string $structure = Structure::class;

    protected string $type = 'symfony-bundle';

    protected array $autoload_dev = [];

    protected string $autoload_path = '';
}
