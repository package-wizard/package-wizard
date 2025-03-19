<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Questions;

use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class QuestionLicenseFileData extends Data
{
    public string $path = 'LICENSE';

    #[WithCast(ArrayWrapCast::class)]
    public array $replace = [':licensePath:'];
}
