<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use PackageWizard\Installer\Data\Casts\NormalizePathCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class CopyData extends Data
{
    #[WithCast(NormalizePathCast::class)]
    public string $source;

    #[WithCast(NormalizePathCast::class)]
    public string $target;

    public bool $absolute = false;

    public bool $asked = false;
}
