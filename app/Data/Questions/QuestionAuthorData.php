<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Questions;

use PackageWizard\Installer\Data\AuthorData;
use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class QuestionAuthorData extends Data
{
    public TypeEnum $type = TypeEnum::Author;

    public AuthorData $author;

    public bool $ask = true;

    #[WithCast(ArrayWrapCast::class)]
    public array $replace = [':author:'];
}
