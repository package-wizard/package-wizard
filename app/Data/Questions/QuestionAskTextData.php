<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Questions;

use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use PackageWizard\Installer\Enums\PromptEnum;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;

class QuestionAskTextData extends QuestionData
{
    public TypeEnum $type = TypeEnum::Ask;

    public PromptEnum $prompt = PromptEnum::Text;

    #[WithCast(ArrayWrapCast::class)]
    public array $replace;

    public string $question;

    public string $default = '';

    public string $placeholder = '';

    public ?string $regex = null;

    public bool $trim = true;

    public bool|string $required = true;
}
