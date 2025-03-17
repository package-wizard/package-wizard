<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Concerns\Data;

use BackedEnum;
use Illuminate\Support\Collection;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use UnexpectedValueException;

trait ChoiceData
{
    abstract protected function map(string|TypeEnum $type, array $item): Data;

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

    protected function type(string|TypeEnum $type): ?TypeEnum
    {
        if ($type instanceof TypeEnum) {
            return $type;
        }

        return TypeEnum::tryFrom($type);
    }

    protected function throw(BackedEnum|string $type): void
    {
        $name = $type->value ?? $type;

        throw new UnexpectedValueException('Unsupported type: ' . $name);
    }
}
