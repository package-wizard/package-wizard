<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Questions;

use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use PackageWizard\Installer\Data\ConditionData;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class QuestionLicenseData extends Data
{
    public TypeEnum $type = TypeEnum::License;

    public ConditionData|true $condition = true;

    public ?string $id = null;

    public string $default;

    #[WithCast(ArrayWrapCast::class)]
    public array $replace;

    public string $filename;
}
