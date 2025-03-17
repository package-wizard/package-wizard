<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use PackageWizard\Installer\Enums\ComparatorEnum;
use Spatie\LaravelData\Data;

class ConditionData extends Data
{
    public string $for;

    public ComparatorEnum $comparator = ComparatorEnum::EqualTo;

    public array|int|string $value;
}
