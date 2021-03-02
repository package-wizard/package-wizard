<?php

namespace Helldar\PackageWizard\Resources;

use Helldar\PackageWizard\Facades\Licenses;
use Helldar\Support\Facades\Helpers\Arr;

final class License extends BaseResource
{
    public function toString(): string
    {
        $this->log('Stringable parser: ', self::class);

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
        $path = $this->getPath($this->getLicense()) ?: $this->getPath(Licenses::getDefault());

        $this->log('Getting the path to the license file:', $path);

        return $path;
    }

    protected function getPath(string $filename): string
    {
        return realpath(__DIR__ . '/../../resources/licenses/' . $filename . '.stub');
    }
}
