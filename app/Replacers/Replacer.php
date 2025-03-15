<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Replacers;

use PackageWizard\Installer\Data\ReplaceData;
use Spatie\LaravelData\Data;

abstract class Replacer
{
    abstract protected function with(): int|string;

    public function __construct(
        protected ?Data $data
    ) {}

    public static function get(?Data $data): ?ReplaceData
    {
        if ($data) {
            return (new static($data))->make();
        }

        return null;
    }

    protected function make(): ReplaceData
    {
        return ReplaceData::from([
            'replace' => $this->data->replace,
            'with'    => $this->with(),
        ]);
    }
}
