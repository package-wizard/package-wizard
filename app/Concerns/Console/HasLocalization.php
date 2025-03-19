<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Concerns\Console;

use PackageWizard\Installer\Data\ConfigData;

use function blank;
use function is_dir;
use function PackageWizard\Installer\base_path;

/** @mixin \Illuminate\Console\Command */
trait HasLocalization
{
    protected function setLocale(?ConfigData $config = null): void
    {
        $this->applyLocale(
            $this->option('lang') ?: $config?->wizard?->localization
        );
    }

    protected function applyLocale(?string $locale): void
    {
        if ($this->allowLocale($locale)) {
            app()->setLocale($locale);
        }
    }

    protected function allowLocale(?string $locale): bool
    {
        if ($locale === '.' || blank($locale)) {
            return false;
        }

        return is_dir(base_path('lang/' . $locale));
    }
}
