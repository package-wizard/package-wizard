<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use PackageWizard\Installer\Enums\ConditionOperatorEnum;
use Spatie\LaravelData\Data;

class ConditionData extends Data
{
    public string $for;

    public ConditionOperatorEnum $operator = ConditionOperatorEnum::EqualTo;

    public array|int|string $value;
}
