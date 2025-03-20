<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers\Questions;

use DragonCode\Support\Facades\Helpers\Arr;
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
    protected ?array $list = null;

    public function __construct(
        protected QuestionLicenseData $data,
        protected FilesystemService $filesystem,
    ) {}

    public function get(): array
    {
        $filename = $this->answer();

        return [
            $this->replaceData($this->data->replace, $this->getName($filename), true),
            $this->replaceData($this->data->file->replace, $this->data->file->path),
            $this->copyData($filename),
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

    protected function copyData(string $filename): CopyData
    {
        return CopyData::from([
            'source'   => resource_path('licenses/' . $filename),
            'target'   => $this->data->file->path,
            'absolute' => true,
        ]);
    }

    protected function answer(): string
    {
        return select(
            label  : __('Which is license will be distributed?'),
            options: $this->available(),
            default: $this->data->default,
            scroll : 15
        );
    }

    protected function getName(string $filename): string
    {
        return Arr::get($this->available(), $filename, 'MIT License');
    }

    protected function available(): array
    {
        return $this->list ??= Arr::ofFile(resource_path('licenses/list.json'))->toArray();
    }
}
