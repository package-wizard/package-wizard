<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data\Normalizers;

use JsonException;
use Spatie\LaravelData\Normalizers\Normalized\Normalized;
use Spatie\LaravelData\Normalizers\Normalizer;

use function file_get_contents;
use function is_file;
use function is_string;
use function json_decode;
use function json_validate;

class JsonNormalizer implements Normalizer
{
    /**
     * @throws JsonException
     */
    public function normalize(mixed $value): array|Normalized|null
    {
        if (! is_string($value)) {
            return null;
        }

        if (is_file($value)) {
            $value = file_get_contents($value);
        }

        if (json_validate($value)) {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }

        return null;
    }
}
