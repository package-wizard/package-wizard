<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Variables;

use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class VariableYearRangeData extends Data
{
    public TypeEnum $type = TypeEnum::YearRange;

    #[WithCast(ArrayWrapCast::class)]
    public array $replace = [':year:'];

    public int $start = 1970;
}
