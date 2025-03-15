<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Casts;

use PackageWizard\Installer\ChoiceableData;
use PackageWizard\Installer\Data\Questions\QuestionAskData;
use PackageWizard\Installer\Data\Questions\QuestionAuthorData;
use PackageWizard\Installer\Data\Questions\QuestionLicenseData;
use PackageWizard\Installer\Data\ReplaceData;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Data;

class QuestionsCast implements Cast
{
    use ChoiceableData;

    protected function map(string|TypeEnum $type, array $item): Data
    {
        return match ($type) {
            TypeEnum::License => QuestionLicenseData::from($item),
            TypeEnum::Ask     => QuestionAskData::from($item),
            TypeEnum::Author  => QuestionAuthorData::from($item),
            TypeEnum::Replace => ReplaceData::from($item),
            default           => $this->throw($type)
        };
    }
}
