<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Questions;

use PackageWizard\Installer\Data\Casts\ArrayWrapCast;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Attributes\WithCast;

class QuestionLicenseData extends QuestionData
{
    public TypeEnum $type = TypeEnum::License;

    public string $default = 'mit';

    #[WithCast(ArrayWrapCast::class)]
    public array $replace = [':license:'];

    public QuestionLicenseFileData $file;

    public static function prepareForPipeline(array $properties): array
    {
        $properties['file'] ??= [];

        return $properties;
    }
}
