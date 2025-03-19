<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers\Questions;

use PackageWizard\Installer\Data\CopyData;
use PackageWizard\Installer\Data\Questions\QuestionLicenseData;
use PackageWizard\Installer\Data\ReplaceData;
use PackageWizard\Installer\Fillers\Filler;
use PackageWizard\Installer\Services\FilesystemService;
use Spatie\LaravelData\Data;

use function Laravel\Prompts\select;
use function PackageWizard\Installer\resource_path;

/** @method static make(QuestionLicenseData|Data $data) */
class LicenseFiller extends Filler
{
    public function __construct(
        protected QuestionLicenseData $data,
        protected FilesystemService $filesystem,
    ) {}

    public function get(): array
    {
        $name = $this->answer();

        return [
            $this->replaceData($this->data->replace, $name, true),
            $this->replaceData($this->data->file->replace, $this->data->file->path),
            $this->copyData($name),
        ];
    }

    protected function replaceData(array $replace, string $with, bool $withId = false): ReplaceData
    {
        return ReplaceData::from([
            'id'      => $withId ? $this->data->id : null,
            'replace' => $replace,
            'with'    => $with,
        ]);
    }

    protected function copyData(string $name): CopyData
    {
        return CopyData::from([
            'source'   => resource_path('licenses/' . $name),
            'target'   => $this->data->file->path,
            'absolute' => true,
        ]);
    }

    protected function answer(): string
    {
        return select(
            label  : __('form.field.license'),
            options: $this->available(),
            default: $this->data->default,
            scroll : 15
        );
    }

    protected function available(): array
    {
        return $this->filesystem->names(
            resource_path('licenses')
        );
    }
}
