<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Replacers;

use PackageWizard\Installer\Data\ReplaceData;
use Spatie\LaravelData\Data;

abstract class Replacer
{
    abstract protected function with(): int|string;

    public function __construct(
        protected ?Data $data,
        protected bool $asked = false
    ) {}

    public static function get(?Data $data, bool $asked = false): ?ReplaceData
    {
        if ($data) {
            return (new static($data, $asked))->make();
        }

        return null;
    }

    protected function make(): ReplaceData
    {
        return ReplaceData::from([
            'replace' => $this->data->replace,
            'with'    => $this->with(),
            'asked'   => $this->asked,
        ]);
    }
}
