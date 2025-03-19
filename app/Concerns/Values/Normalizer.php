<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Concerns\Values;

use function is_array;
use function is_numeric;

trait Normalizer
{
    protected function normalize(array|int|string|null $value): array|int|string|null
    {
        if ($value === null) {
            return null;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        if (is_array($value)) {
            foreach ($value as &$item) {
                $item = $this->normalize($item);
            }
        }

        return $value;
    }
}
