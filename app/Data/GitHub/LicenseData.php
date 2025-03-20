<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\GitHub;

use PackageWizard\Installer\Data\Casts\GitHub\LicenseCast;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class LicenseData extends Data
{
    #[MapInputName('key')]
    public string $filename;

    #[MapInputName('spdx_id')]
    public string $id;

    #[MapInputName('name')]
    public string $name;

    #[MapInputName('body')]
    #[WithCast(LicenseCast::class)]
    public Optional|string $content;
}
