<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use Illuminate\Support\Str;
use PackageWizard\Installer\Enums\ConditionOperatorEnum;

use function in_array;

class ComparatorService
{
    protected array $default = [
        ConditionOperatorEnum::EqualTo,
        ConditionOperatorEnum::NotEqualTo,
    ];

    protected array $array = [
        ConditionOperatorEnum::InList,
        ConditionOperatorEnum::NotInList,
    ];

    protected array $numbers = [
        ConditionOperatorEnum::LessThan,
        ConditionOperatorEnum::LessThanOrEqualTo,
        ConditionOperatorEnum::GreaterThan,
        ConditionOperatorEnum::GreaterThanOrEqualTo,
    ];

    protected array $strings = [
        ConditionOperatorEnum::Contains,
        ConditionOperatorEnum::DoesntContainAll,
        ConditionOperatorEnum::ContainsAll,
        ConditionOperatorEnum::DoesntContain,
    ];

    public function disallow(ConditionOperatorEnum $comparator, array|int|string $haystack, array|int|string $needle): bool
    {
        return ! $this->allow($comparator, $haystack, $needle);
    }

    public function allow(ConditionOperatorEnum $comparator, array|int|string $haystack, array|int|string $needle): bool
    {
        return match (true) {
            in_array($comparator, $this->default, true) => $this->default($comparator, $haystack, $needle),
            in_array($comparator, $this->array, true)   => $this->array($comparator, (array) $haystack, $needle),
            in_array($comparator, $this->numbers, true) => $this->numbers($comparator, (int) $haystack, (int) $needle),
            in_array($comparator, $this->strings, true) => $this->strings($comparator, $haystack, $needle),
        };
    }

    protected function default(ConditionOperatorEnum $comparator, array|int|string $haystack, array|int|string $needle): bool
    {
        if ($comparator === ConditionOperatorEnum::EqualTo && $haystack === $needle) {
            return true;
        }

        return $comparator === ConditionOperatorEnum::NotEqualTo && $haystack !== $needle;
    }

    protected function array(ConditionOperatorEnum $comparator, array $haystack, int|string $needle): bool
    {
        $compare = in_array($needle, $haystack, true);

        if ($comparator === ConditionOperatorEnum::InList && $compare === true) {
            return true;
        }

        return $comparator === ConditionOperatorEnum::NotInList && $compare === false;
    }

    protected function numbers(ConditionOperatorEnum $comparator, int $haystack, int $needle): bool
    {
        if ($comparator === ConditionOperatorEnum::LessThan && $needle < $haystack) {
            return true;
        }

        if ($comparator === ConditionOperatorEnum::LessThanOrEqualTo && $needle <= $haystack) {
            return true;
        }

        if ($comparator === ConditionOperatorEnum::GreaterThan && $needle > $haystack) {
            return true;
        }

        return $comparator === ConditionOperatorEnum::GreaterThanOrEqualTo && $needle >= $haystack;
    }

    protected function strings(ConditionOperatorEnum $comparator, mixed $haystack, mixed $needle): bool
    {
        if ($comparator === ConditionOperatorEnum::Contains && Str::contains($needle, $haystack)) {
            return true;
        }

        if ($comparator === ConditionOperatorEnum::ContainsAll && Str::containsAll($needle, $haystack)) {
            return true;
        }

        if ($comparator === ConditionOperatorEnum::DoesntContain && ! Str::contains($needle, $haystack)) {
            return true;
        }

        return $comparator === ConditionOperatorEnum::DoesntContainAll && ! Str::containsAll($needle, $haystack);
    }
}
