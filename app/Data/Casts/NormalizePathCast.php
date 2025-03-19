<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Casts;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class NormalizePathCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): string
    {
        return Str::of($value)
            ->replace('\\', '/')
            ->when(! $this->isAbsolute($properties), fn (Stringable $str) => $str->ltrim('/'))
            ->rtrim('/')
            ->toString();
    }

    protected function isAbsolute(array $properties): bool
    {
        return $properties['absolute'] ?? false;
    }
}
