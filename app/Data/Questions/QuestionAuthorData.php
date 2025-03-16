<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Questions;

use PackageWizard\Installer\Data\AuthorData;
use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;

class QuestionAuthorData extends QuestionData
{
    public TypeEnum $type = TypeEnum::Author;

    public AuthorData $author;

    #[WithCast(ArrayWrapCast::class)]
    public array $replace = [':author:'];

    public string $format = ':name: <:email:>';
}
