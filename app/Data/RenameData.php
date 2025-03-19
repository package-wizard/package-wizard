<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use PackageWizard\Installer\Data\Casts\NormalizePathCast;
use PackageWizard\Installer\Enums\RenameEnum;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class RenameData extends Data
{
    public TypeEnum $type = TypeEnum::Rename;

    public RenameEnum $what;

    #[WithCast(NormalizePathCast::class)]
    public string $source;

    #[WithCast(NormalizePathCast::class)]
    public string $target;

    public bool $asked = false;
}
