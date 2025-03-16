<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use PackageWizard\Installer\Enums\DependencyTypeEnum;
use Spatie\LaravelData\Data;

class DependencyData extends Data
{
    public DependencyTypeEnum $type = DependencyTypeEnum::Composer;

    public string $name;

    public bool $dev = false;

    public bool $remove = false;
}
