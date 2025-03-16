<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Questions;

use PackageWizard\Installer\Data\ConditionData;
use Spatie\LaravelData\Data;

abstract class QuestionData extends Data
{
    public ConditionData|true $condition = true;

    public ?string $id = null;
}
