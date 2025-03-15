<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Casts;

use PackageWizard\Installer\ChoiceableData;
use PackageWizard\Installer\Data\Questions\QuestionAskSelectData;
use PackageWizard\Installer\Data\Questions\QuestionAskTextData;
use PackageWizard\Installer\Data\Questions\QuestionAuthorData;
use PackageWizard\Installer\Data\Questions\QuestionLicenseData;
use PackageWizard\Installer\Enums\PromptEnum;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Data;

class QuestionsCast implements Cast
{
    use ChoiceableData;

    protected function map(string|TypeEnum $type, array $item): Data
    {
        return match ($this->type($type)) {
            TypeEnum::Ask     => $this->ask($item),
            TypeEnum::Author  => QuestionAuthorData::from($item),
            TypeEnum::License => QuestionLicenseData::from($item),
            default           => $this->throw($type)
        };
    }

    protected function ask(array $item): Data
    {
        return match ($this->prompt($item['prompt'] ?? null)) {
            PromptEnum::Select => QuestionAskSelectData::from($item),
            default            => QuestionAskTextData::from($item)
        };
    }

    protected function prompt(PromptEnum|string|null $prompt): ?PromptEnum
    {
        if ($prompt === null) {
            return null;
        }

        if ($prompt instanceof PromptEnum) {
            return $prompt;
        }

        return PromptEnum::tryFrom($prompt);
    }
}
