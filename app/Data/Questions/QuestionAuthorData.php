<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Questions;

use PackageWizard\Installer\Data\AuthorData;
use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use PackageWizard\Installer\Data\ConditionData;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class QuestionAuthorData extends Data
{
    public TypeEnum $type = TypeEnum::Author;

    public ConditionData|true $condition = true;

    public ?string $id = null;

    public AuthorData $author;

    #[WithCast(ArrayWrapCast::class)]
    public array $replace = [':author:'];

    public string $format = ':name: <:email:>';
}
