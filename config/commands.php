<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

return [
    'default' => NewCommand::class,

    'paths' => [app_path('Commands')],

    'add' => [],

    'hidden' => [
        NunoMaduro\LaravelConsoleSummary\SummaryCommand::class,
        Symfony\Component\Console\Command\DumpCompletionCommand::class,
        Symfony\Component\Console\Command\HelpCommand::class,
        Illuminate\Console\Scheduling\ScheduleRunCommand::class,
        Illuminate\Console\Scheduling\ScheduleListCommand::class,
        Illuminate\Console\Scheduling\ScheduleFinishCommand::class,
        Illuminate\Foundation\Console\VendorPublishCommand::class,
        LaravelZero\Framework\Commands\StubPublishCommand::class,
    ],

    'remove' => [],
];
