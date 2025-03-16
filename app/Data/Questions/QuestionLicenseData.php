<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Questions;

use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;

class QuestionLicenseData extends QuestionData
{
    public TypeEnum $type = TypeEnum::License;

    public string $default;

    #[WithCast(ArrayWrapCast::class)]
    public array $replace;

    public string $filename;
}
