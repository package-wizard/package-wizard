<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers\Questions;

use Closure;
use Illuminate\Support\Str;
use PackageWizard\Installer\Data\Questions\QuestionAskTextData;
use PackageWizard\Installer\Data\ReplaceData;
use PackageWizard\Installer\Fillers\Filler;
use Spatie\LaravelData\Data;

use function blank;
use function Laravel\Prompts\text;
use function trim;

/** @method static make(QuestionAskTextData|Data $data) */
class AskTextFiller extends Filler
{
    public function __construct(
        protected QuestionAskTextData $data
    ) {}

    public function get(): ?ReplaceData
    {
        if (! $answer = $this->cleanup($this->answer())) {
            return null;
        }

        return ReplaceData::from([
            'replace' => $this->data->replace,
            'with'    => $answer,
        ]);
    }

    protected function answer(): string
    {
        return text(
            label      : $this->data->question,
            placeholder: $this->data->placeholder,
            default    : $this->data->default,
            required   : $this->data->required,
            validate   : $this->validator(),
            hint       : ! $this->data->required ? 'Press Enter to continue if you want to leave the field blank' : '',
        );
    }

    protected function cleanup(string $value): string
    {
        if ($this->data->trim) {
            return trim($value);
        }

        return $value;
    }

    protected function validator(): ?Closure
    {
        if (! $this->data->regex) {
            return null;
        }

        return function (string $value): ?string {
            if (! $this->data->required && blank($value)) {
                return null;
            }

            if (Str::isMatch($this->data->regex, $value)) {
                return null;
            }

            return 'The value does not match the format: ' . $this->data->regex;
        };
    }
}
