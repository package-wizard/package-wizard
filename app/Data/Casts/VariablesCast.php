<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Casts;

use PackageWizard\Installer\Concerns\Data\ChoiceData;
use PackageWizard\Installer\Data\Variables\VariableDateData;
use PackageWizard\Installer\Data\Variables\VariableYearData;
use PackageWizard\Installer\Data\Variables\VariableYearRangeData;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Data;

class VariablesCast implements Cast
{
    use ChoiceData;

    protected function map(string|TypeEnum $type, array $item): Data
    {
        return match ($this->type($type)) {
            TypeEnum::Year      => VariableYearData::from($item),
            TypeEnum::YearRange => VariableYearRangeData::from($item),
            TypeEnum::Date      => VariableDateData::from($item),
            default             => $this->throw($type),
        };
    }
}
