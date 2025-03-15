<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers\Questions;

use PackageWizard\Installer\Data\Questions\QuestionAskSelectData;
use PackageWizard\Installer\Data\ReplaceData;
use PackageWizard\Installer\Fillers\Filler;
use Spatie\LaravelData\Data;

use function Laravel\Prompts\select;

/** @method static make(QuestionAskSelectData|Data $data) */
class AskSelectFiller extends Filler
{
    public function __construct(
        protected QuestionAskSelectData $data
    ) {}

    public function get(): ReplaceData
    {
        return ReplaceData::from([
            'replace' => $this->data->replace,
            'with'    => $this->answer(),
        ]);
    }

    protected function answer(): string
    {
        return select(
            label   : $this->data->question,
            options : $this->data->options,
            default : $this->data->default,
            scroll  : 20,
            hint    : ! $this->data->required ? 'Press Enter to continue if you want to leave the field blank' : '',
            required: $this->data->required
        );
    }
}
