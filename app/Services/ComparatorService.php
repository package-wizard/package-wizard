<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use Illuminate\Support\Str;
use PackageWizard\Installer\Enums\ComparatorEnum;

use function in_array;

class ComparatorService
{
    protected array $default = [
        ComparatorEnum::EqualTo,
        ComparatorEnum::NotEqualTo,
    ];

    protected array $array = [
        ComparatorEnum::InList,
        ComparatorEnum::NotInList,
    ];

    protected array $numbers = [
        ComparatorEnum::LessThan,
        ComparatorEnum::LessThanOrEqualTo,
        ComparatorEnum::GreaterThan,
        ComparatorEnum::GreaterThanOrEqualTo,
    ];

    protected array $strings = [
        ComparatorEnum::Contains,
        ComparatorEnum::DoesntContainAll,
        ComparatorEnum::ContainsAll,
        ComparatorEnum::DoesntContain,
    ];

    public function disallow(ComparatorEnum $comparator, array|int|string $haystack, array|int|string $needle): bool
    {
        return ! $this->allow($comparator, $haystack, $needle);
    }

    public function allow(ComparatorEnum $comparator, array|int|string $haystack, array|int|string $needle): bool
    {
        return match (true) {
            in_array($comparator, $this->default, true) => $this->default($comparator, $haystack, $needle),
            in_array($comparator, $this->array, true)   => $this->array($comparator, (array) $haystack, $needle),
            in_array($comparator, $this->numbers, true) => $this->numbers($comparator, (int) $haystack, (int) $needle),
            in_array($comparator, $this->strings, true) => $this->strings($comparator, $haystack, $needle),
        };
    }

    protected function default(ComparatorEnum $comparator, array|int|string $haystack, array|int|string $needle): bool
    {
        if ($comparator === ComparatorEnum::EqualTo && $haystack === $needle) {
            return true;
        }

        return $comparator === ComparatorEnum::NotEqualTo && $haystack !== $needle;
    }

    protected function array(ComparatorEnum $comparator, array $haystack, int|string $needle): bool
    {
        $compare = in_array($needle, $haystack, true);

        if ($comparator === ComparatorEnum::InList && $compare === true) {
            return true;
        }

        return $comparator === ComparatorEnum::NotInList && $compare === false;
    }

    protected function numbers(ComparatorEnum $comparator, int $haystack, int $needle): bool
    {
        if ($comparator === ComparatorEnum::LessThan && $needle < $haystack) {
            return true;
        }

        if ($comparator === ComparatorEnum::LessThanOrEqualTo && $needle <= $haystack) {
            return true;
        }

        if ($comparator === ComparatorEnum::GreaterThan && $needle > $haystack) {
            return true;
        }

        return $comparator === ComparatorEnum::GreaterThanOrEqualTo && $needle >= $haystack;
    }

    protected function strings(ComparatorEnum $comparator, mixed $haystack, mixed $needle): bool
    {
        if ($comparator === ComparatorEnum::Contains && Str::contains($needle, $haystack)) {
            return true;
        }

        if ($comparator === ComparatorEnum::ContainsAll && Str::containsAll($needle, $haystack)) {
            return true;
        }

        if ($comparator === ComparatorEnum::DoesntContain && ! Str::contains($needle, $haystack)) {
            return true;
        }

        return $comparator === ComparatorEnum::DoesntContainAll && ! Str::containsAll($needle, $haystack);
    }
}
