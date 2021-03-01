<?php

namespace Helldar\PackageWizard\Resources;

use Helldar\PackageWizard\Constants\Licenses;
use Helldar\Support\Facades\Helpers\Arr;
use Helldar\Support\Facades\Helpers\Str;

final class License extends BaseResource
{
    public function toString(): string
    {
        return $this->getParser()
            ->replace('license', $this->getLicense())
            ->replace('year', $this->getYear())
            ->replace('authors', $this->getAuthors())
            ->get();
    }

    protected function getLicense(): string
    {
        return $this->stepper->getLicense();
    }

    protected function getYear(): int
    {
        return date('Y');
    }

    protected function getAuthors(): string
    {
        $authors = array_map(static function ($value) {
            return Arr::get($value, 'name');
        }, $this->stepper->getAuthors());

        return implode(', ', $authors);
    }

    protected function path(): string
    {
        return $this->getPath($this->getLicense()) ?: $this->getPath(Licenses::DEFAULT_LICENSE);
    }

    protected function getPath(string $filename): string
    {
        $filename = Str::lower($filename);

        return realpath(__DIR__ . '/../../resources/licenses/' . $filename . '.stub');
    }
}
