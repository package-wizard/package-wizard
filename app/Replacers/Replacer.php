<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Replacers;

use PackageWizard\Installer\Data\ReplaceData;
use Spatie\LaravelData\Data;

abstract class Replacer
{
    abstract protected function with(): int|string;

    public function __construct(
        protected array|Data|null $data,
        protected bool $asked = false
    ) {}

    public static function get(array|Data|null $data, bool $asked = false): array|Data|null
    {
        if (filled($data)) {
            return (new static($data, $asked))->make();
        }

        return null;
    }

    protected function make(): array|Data|null
    {
        return ReplaceData::from([
            'id'      => $this->data->id ?? null,
            'replace' => $this->data->replace,
            'with'    => $this->with(),
            'asked'   => $this->asked,
        ]);
    }
}
