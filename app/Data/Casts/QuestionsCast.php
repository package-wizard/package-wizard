<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Casts;

use Illuminate\Support\Collection;
use PackageWizard\Installer\Data\Questions\QuestionLicenseData;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

use function collect;

class QuestionsCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): Collection
    {
        return collect($value)->map(
            fn (array $item) => $this->map($item['type'], $item)
        );
    }

    protected function map(string $type, array $item): Data
    {
        return match ($type) {
            TypeEnum::License->value => QuestionLicenseData::from($item),
        };
    }
}
