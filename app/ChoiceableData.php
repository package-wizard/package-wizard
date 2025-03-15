<?php

declare(strict_types=1);

namespace PackageWizard\Installer;

use Illuminate\Support\Collection;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use UnexpectedValueException;

trait ChoiceableData
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
        return $type instanceof TypeEnum ? $type : TypeEnum::tryFrom($type);
    }

    protected function throw(string|TypeEnum $type): void
    {
        $name = $this->type($type)?->value ?? $type;

        throw new UnexpectedValueException('Unsupported type: ' . $name);
    }
}
