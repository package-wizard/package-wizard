<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Casts\GitHub;

use Illuminate\Support\Str;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class LicenseCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): string
    {
        return Str::of($value)
            ->replace([
                '[year]',
                '<year>',
                '[yyyy]',
            ], ':year:')
            ->replace([
                '[fullname]',
                '<name of author>',
                '[name of copyright owner]',
            ], ':author:')
            ->replace([
                'Copyright (C) year name of author',
            ], 'Copyright (C) :year: :author:')
            ->toString();
    }
}
