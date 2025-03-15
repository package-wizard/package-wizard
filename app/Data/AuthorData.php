<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class AuthorData extends Data
{
    public ?string $name = null;

    public ?string $email = null;

    #[WithCast(ArrayWrapCast::class)]
    public array $replace = [':author:'];

    public string $format = ':name: <:email:>';
}
