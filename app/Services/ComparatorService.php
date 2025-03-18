<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use DragonCode\Support\Facades\Filesystem\Directory;
use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PackageWizard\Installer\Enums\ConditionOperatorEnum;

use function in_array;

class ComparatorService
{
    public function disallow(
        ConditionOperatorEnum $comparator,
        array|int|string $needle,
        array|int|string $haystack
    ): bool {
        return ! $this->allow($comparator, $needle, $haystack);
    }

    public function allow(ConditionOperatorEnum $comparator, array|int|string $needle, array|int|string $haystack): bool
    {
        return match ($comparator) {
            ConditionOperatorEnum::LessThan             => $this->lessThan($needle, $haystack),
            ConditionOperatorEnum::LessThanOrEqualTo    => $this->lessThanOrEqualTo($needle, $haystack),
            ConditionOperatorEnum::EqualTo              => $this->equalTo($needle, $haystack),
            ConditionOperatorEnum::NotEqualTo           => $this->notEqualTo($needle, $haystack),
            ConditionOperatorEnum::GreaterThan          => $this->greaterThan($needle, $haystack),
            ConditionOperatorEnum::GreaterThanOrEqualTo => $this->greaterThanOrEqualTo($needle, $haystack),
            ConditionOperatorEnum::InList               => $this->inList($needle, $haystack),
            ConditionOperatorEnum::NotInList            => $this->notInList($needle, $haystack),
            ConditionOperatorEnum::Contains             => $this->contains($needle, $haystack),
            ConditionOperatorEnum::DoesntContain        => $this->doesntContain($needle, $haystack),
            ConditionOperatorEnum::ContainsAll          => $this->containsAll($needle, $haystack),
            ConditionOperatorEnum::DoesntContainAll     => $this->doesntContainAll($needle, $haystack),
            ConditionOperatorEnum::ExistsPath           => $this->existsPath($haystack),
            ConditionOperatorEnum::DoesNotExistPath     => $this->doesNotExistPath($haystack),
        };
    }

    protected function lessThan(array|int|string $needle, array|int|string $haystack): bool
    {
        return $needle < $haystack;
    }

    protected function lessThanOrEqualTo(array|int|string $needle, array|int|string $haystack): bool
    {
        return $needle <= $haystack;
    }

    protected function equalTo(array|int|string $needle, array|int|string $haystack): bool
    {
        return $needle === $haystack;
    }

    protected function notEqualTo(array|int|string $needle, array|int|string $haystack): bool
    {
        return $needle !== $haystack;
    }

    protected function greaterThan(array|int|string $needle, array|int|string $haystack): bool
    {
        return $needle > $haystack;
    }

    protected function greaterThanOrEqualTo(array|int|string $needle, array|int|string $haystack): bool
    {
        return $needle >= $haystack;
    }

    protected function inList(int|string $needle, array|int|string $haystack): bool
    {
        return in_array($needle, Arr::wrap($haystack), true);
    }

    protected function notInList(int|string $needle, array|int|string $haystack): bool
    {
        return ! in_array($needle, Arr::wrap($haystack), true);
    }

    protected function contains(int|string $needle, array|int|string $haystack): bool
    {
        return Str::contains($needle, $haystack);
    }

    protected function doesntContain(int|string $needle, array|int|string $haystack): bool
    {
        return Str::doesntContain($needle, $haystack);
    }

    protected function containsAll(int|string $needle, array|int|string $haystack): bool
    {
        return Str::containsAll($needle, $haystack);
    }

    protected function doesntContainAll(array|int|string $needle, array|int|string $haystack): bool
    {
        return ! Str::containsAll($needle, $haystack);
    }

    protected function existsPath(string $haystack): bool
    {
        return File::exists($haystack) || Directory::exists($haystack);
    }

    protected function doesNotExistPath(string $haystack): bool
    {
        return ! File::exists($haystack) && ! Directory::exists($haystack);
    }
}
