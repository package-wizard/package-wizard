<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers;

use PackageWizard\Installer\Data\ReplaceData;
use PackageWizard\Installer\Enums\PromptEnum;
use PackageWizard\Installer\Fillers\Questions\AskSelectFiller;
use PackageWizard\Installer\Fillers\Questions\AskTextFiller;
use Spatie\LaravelData\Data;

/** @method static make(Data $data) */
class AskFiller extends Filler
{
    public function __construct(
        protected Data $data
    ) {}

    public function get(): ?ReplaceData
    {
        return match ($this->data->prompt) {
            PromptEnum::Select => AskSelectFiller::make(data: $this->data),
            PromptEnum::Text   => AskTextFiller::make(data: $this->data),
        };
    }
}
