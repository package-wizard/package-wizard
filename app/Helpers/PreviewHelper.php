<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Helpers;

use DragonCode\Support\Facades\Helpers\Boolean;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\HigherOrderTapProxy;
use PackageWizard\Installer\Data\ConfigData;
use PackageWizard\Installer\Data\ReplaceData;
use PackageWizard\Installer\Data\WizardData;
use PackageWizard\Installer\Enums\TypeEnum;
use Spatie\LaravelData\Data;

use function collect;
use function date;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function PackageWizard\Installer\vendor_path;
use function tap;
use function Termwind\render;

class PreviewHelper
{
    public static function show(ConfigData $data, Factory $output): void
    {
        static::wizard($data->wizard, $output);
        static::authors($data->authors, $output);
        static::variables($data->variables, $output);
        static::replaces($data->replaces, $output);
    }

    protected static function wizard(WizardData $data, Factory $output): void
    {
        $output->info('Wizard');

        static::twoColumnDetail('Install', Boolean::toString($data->install));
        static::twoColumnDetail('Clean', Boolean::toString($data->clean));
    }

    protected static function authors(Collection $items, Factory $output): void
    {
        $output->info('Authors');

        $items->each(
            fn (ReplaceData $data) => static::twoColumnDetail(static::compact($data->replace), $data->with)
        );
    }

    protected static function variables(Collection $items, Factory $output): void
    {
        $output->info('Variables');

        $items->each(function (Data $data) {
            $with = match ($data->type) {
                TypeEnum::Year      => date('Y'),
                TypeEnum::YearRange => $data->start === (int) date('Y') ? $data->start : $data->start . '-' . date('Y'),
                TypeEnum::Date      => date($data->format),
            };

            static::twoColumnDetail(static::compact($data->replace), $with);
        });
    }

    protected static function replaces(Collection $items, Factory $output): void
    {
        $output->info('Replace');

        $items->each(
            fn (ReplaceData $data) => static::twoColumnDetail(static::compact($data->replace), $data->with)
        );
    }

    protected static function compact(array $values): string
    {
        return collect($values)->join(', ', ' and ');
    }

    protected static function twoColumnDetail(string $first, int|string $second): void
    {
        render((string) static::compile('two-column-detail', $first, $second));
    }

    protected static function compile(string $view, mixed $first, int|string $second): false|HigherOrderTapProxy|string
    {
        ob_start();

        include vendor_path("illuminate/console/resources/views/components/$view.php");

        return tap(ob_get_contents(), static fn () => ob_end_clean());
    }
}
