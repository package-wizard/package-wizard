<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Variables;

use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class VariableDateData extends Data
{
    public TypeEnum $type = TypeEnum::Date;

    #[WithCast(ArrayWrapCast::class)]
    public array $replace = [':date:'];

    public string $format = 'Y-m-d';
}
