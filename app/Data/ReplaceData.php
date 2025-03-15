<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class ReplaceData extends Data
{
    public TypeEnum $type = TypeEnum::Replace;

    #[WithCast(ArrayWrapCast::class)]
    public array $replace;

    public string $with;
}
