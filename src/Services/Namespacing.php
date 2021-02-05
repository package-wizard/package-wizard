<?php

namespace Helldar\PackageWizard\Services;

use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Str;

final class Namespacing
{
    use Makeable;

    public const SEPARATOR = '\\';

    protected string $value;

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
        return str_replace('/', self::SEPARATOR, $value);
    }

    protected function explode(string $value): array
    {
        return explode(self::SEPARATOR, $value);
    }

    protected function studly(array $values): array
    {
        return array_map(static fn ($value) => Str::studly($value), $values);
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
