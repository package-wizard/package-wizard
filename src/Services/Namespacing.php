<?php

namespace Helldar\PackageWizard\Services;

use Helldar\PackageWizard\Concerns\Logger;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Str;

final class Namespacing
{
    use Logger;
    use Makeable;

    public const SEPARATOR = '\\';

    /** @var string */
    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function get(): string
    {
        $separated = $this->separable($this->value);

        $split = $this->explode($separated);

        $studly = $this->studly($split);

        $compact = $this->compact($studly);

        return $this->finalization($compact);
    }

    protected function separable(string $value): string
    {
        $this->log('Replacing the splitter "/" to "', self::SEPARATOR, '" in "', $value, '"');

        return str_replace('/', self::SEPARATOR, $value);
    }

    protected function explode(string $value): array
    {
        $this->log('Splitting a"', $value, '" string by "', self::SEPARATOR, '" splitter');

        return explode(self::SEPARATOR, $value);
    }

    protected function studly(array $values): array
    {
        return array_map(static function ($value) {
            return Str::studly($value);
        }, $values);
    }

    protected function compact(array $values): string
    {
        return implode(self::SEPARATOR, $values);
    }

    protected function finalization(string $value): string
    {
        return Str::finish($value, self::SEPARATOR);
    }
}
