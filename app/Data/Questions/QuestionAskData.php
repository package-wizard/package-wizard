<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Questions;

use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use PackageWizard\Installer\Enums\PromptType;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class QuestionAskData extends Data
{
    public TypeEnum $type = TypeEnum::Ask;

    public PromptType $prompt = PromptType::Text;

    #[WithCast(ArrayWrapCast::class)]
    public array $replace;

    public string $question;

    public ?string $placeholder = null;

    public ?string $regex = null;

    public bool $trim = true;

    public bool $required = true;
}
