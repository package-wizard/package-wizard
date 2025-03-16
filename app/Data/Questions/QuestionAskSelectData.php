<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Questions;

use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use PackageWizard\Installer\Data\ConditionData;
use PackageWizard\Installer\Enums\PromptEnum;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class QuestionAskSelectData extends Data
{
    public TypeEnum $type = TypeEnum::Ask;

    public PromptEnum $prompt = PromptEnum::Select;

    public ConditionData|true $condition = true;

    public ?string $id = null;

    #[WithCast(ArrayWrapCast::class)]
    public array $replace;

    public string $question;

    public array $options;

    public int|string|null $default = null;

    public bool|string $required = true;
}
