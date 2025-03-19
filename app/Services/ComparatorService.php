<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use DragonCode\Support\Facades\Filesystem\Directory;
use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PackageWizard\Installer\Concerns\Values\Normalizer;
use PackageWizard\Installer\Enums\ConditionOperatorEnum;

use function in_array;

class ComparatorService
{
    use Normalizer;

    public function disallow(
        ConditionOperatorEnum $comparator,
        array|int|string|null $first,
        array|int|string|null $second = null,
    ): bool {
        return ! $this->allow($comparator, $first, $second);
    }

    /**
     * @param  array|int|string  $first  the value with which you need to compare
     * @param  array|int|string|null  $second  value from the object received by the identifier
     */
    public function allow(
        ConditionOperatorEnum $comparator,
        array|int|string $first,
        array|int|string|null $second = null
    ): bool {
        $second = $this->normalize($second);
        $first  = $this->normalize($first);

        return match ($comparator) {
            ConditionOperatorEnum::LessThan             => $this->lessThan($second, $first),
            ConditionOperatorEnum::LessThanOrEqualTo    => $this->lessThanOrEqualTo($second, $first),
            ConditionOperatorEnum::EqualTo              => $this->equalTo($second, $first),
            ConditionOperatorEnum::NotEqualTo           => $this->notEqualTo($second, $first),
            ConditionOperatorEnum::GreaterThan          => $this->greaterThan($second, $first),
            ConditionOperatorEnum::GreaterThanOrEqualTo => $this->greaterThanOrEqualTo($second, $first),
            ConditionOperatorEnum::In                   => $this->in($second, $first),
            ConditionOperatorEnum::NotIn                => $this->notIn($second, $first),
            ConditionOperatorEnum::Contains             => $this->contains($second, $first),
            ConditionOperatorEnum::DoesntContain        => $this->doesntContain($second, $first),
            ConditionOperatorEnum::ContainsAll          => $this->containsAll($second, $first),
            ConditionOperatorEnum::DoesntContainAll     => $this->doesntContainAll($second, $first),
            ConditionOperatorEnum::PathExists           => $this->existsPath($first),
            ConditionOperatorEnum::PathDoesNotExist     => $this->doesNotExistPath($first),
        };
    }

    protected function lessThan(int|string $second, int|string $first): bool
    {
        return $second < $first;
    }

    protected function lessThanOrEqualTo(int|string $second, int|string $first): bool
    {
        return $second <= $first;
    }

    protected function equalTo(int|string $second, int|string $first): bool
    {
        return $second === $first;
    }

    protected function notEqualTo(int|string $second, int|string $first): bool
    {
        return $second !== $first;
    }

    protected function greaterThan(int|string $second, int|string $first): bool
    {
        return $second > $first;
    }

    protected function greaterThanOrEqualTo(int|string $second, int|string $first): bool
    {
        return $second >= $first;
    }

    protected function in(int|string $second, array $first): bool
    {
        return in_array($second, $first, true);
    }

    protected function notIn(int|string $second, array $first): bool
    {
        return ! $this->in($second, $first);
    }

    protected function contains(int|string $second, int|string $first): bool
    {
        return Str::contains((string) $first, (string) $second);
    }

    protected function doesntContain(int|string $second, array|int|string $first): bool
    {
        return Str::doesntContain((string) $first, (string) $second);
    }

    protected function containsAll(int|string $second, array|int|string $first): bool
    {
        return Str::containsAll($first, Arr::wrap($second));
    }

    protected function doesntContainAll(int|string $second, array|int|string $first): bool
    {
        return ! $this->containsAll($second, $first);
    }

    protected function existsPath(int|string $first): bool
    {
        return File::exists((string) $first) || Directory::exists((string) $first);
    }

    protected function doesNotExistPath(int|string $first): bool
    {
        return ! File::exists((string) $first) && ! Directory::exists((string) $first);
    }
}
