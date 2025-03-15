<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Replacers;

use PackageWizard\Installer\Data\Variables\VariableDateData;
use PackageWizard\Installer\Data\Variables\VariableYearRangeData;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Data;

use function date;
use function sprintf;

class VariableReplacer extends Replacer
{
    protected function with(): int|string
    {
        return match ($this->data->type) {
            TypeEnum::Date      => $this->date($this->data),
            TypeEnum::Year      => $this->year(),
            TypeEnum::YearRange => $this->yearRange($this->data),
        };
    }

    protected function date(Data|VariableDateData $data): string
    {
        return date($data->format);
    }

    protected function year(): int
    {
        return (int) date('Y');
    }

    protected function yearRange(Data|VariableYearRangeData $data): int|string
    {
        $now = $this->year();

        if ($now === $data->start) {
            return $now;
        }

        return sprintf('%d-%d', $data->start, $now);
    }
}
