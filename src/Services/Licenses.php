<?php

namespace Helldar\PackageWizard\Services;

use Helldar\PackageWizard\Concerns\Logger;
use Helldar\Support\Facades\Helpers\Filesystem\File;
use Helldar\Support\Facades\Helpers\Str;

final class Licenses
{
    use Logger;

    protected $default = 'Other';

    public function available(): array
    {
        $this->log('Getting a list of available licenses.');

        return array_map(function ($name) {
            return $this->clean($name);
        }, $this->all());
    }

    public function get(int $index): ?string
    {
        $this->log('Getting license name by index:', $index);

        return $this->available()[$index] ?? null;
    }

    public function all(): array
    {
        $this->log('Getting a list of license file names');

        return File::names(__DIR__ . '/../../resources/licenses');
    }

    public function getDefault(): string
    {
        $this->log('Obtaining the default license file name');

        return $this->default;
    }

    protected function clean(string $name): string
    {
        $this->log('Getting file name from template format (', $name, ')');

        return Str::endsWith($name, '.stub') ? pathinfo($name, PATHINFO_FILENAME) : $name;
    }
}
