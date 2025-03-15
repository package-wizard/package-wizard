<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Casts;

use Illuminate\Support\Collection;
use PackageWizard\Installer\Data\Variables\VariableDateData;
use PackageWizard\Installer\Data\Variables\VariableYearData;
use PackageWizard\Installer\Data\Variables\VariableYearRangeData;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

use function collect;

class VariablesCast implements Cast
{
    public function cast(
        DataProperty $property,
        mixed $value,
        array $properties,
        CreationContext $context
    ): Collection {
        return collect($value)->map(
            fn (array $item) => $this->map($item['type'], $item)
        );
    }

    protected function map(string $type, array $item): Data
    {
        return match ($type) {
            TypeEnum::Year->value      => VariableYearData::from($item),
            TypeEnum::YearRange->value => VariableYearRangeData::from($item),
            TypeEnum::Date->value      => VariableDateData::from($item),
        };
    }
}
