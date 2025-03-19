<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers\Questions;

use DragonCode\Support\Facades\Filesystem\File;
use PackageWizard\Installer\Data\Questions\QuestionLicenseData;
use PackageWizard\Installer\Data\ReplaceData;
use PackageWizard\Installer\Fillers\Filler;
use Spatie\LaravelData\Data;

use function Laravel\Prompts\select;
use function PackageWizard\Installer\resource_path;

/** @method static make(QuestionLicenseData|Data $data) */
class LicenseFiller extends Filler
{
    public function __construct(
        protected QuestionLicenseData $data
    ) {}

    public function get(): ReplaceData
    {
        return ReplaceData::from([
            'id'      => $this->data->id,
            'replace' => $this->data->replace,
            'with'    => $this->answer(),
        ]);
    }

    protected function answer(): string
    {
        return select(
            label  : 'Which is license will be distributed?',
            options: $this->available(),
            default: $this->data->default,
            scroll : 15
        );
    }

    protected function available(): array
    {
        return File::names(resource_path('licenses'));
    }
}
