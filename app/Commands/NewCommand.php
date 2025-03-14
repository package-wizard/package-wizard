<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class NewCommand extends Command
{
    protected $name = 'new';

    protected $description = 'Create new project';

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, default: 'list')
            ->addOption('local', null, InputOption::VALUE_NONE, 'Set up a local project in the specified folder');
    }

    protected function handle(): int
    {
        return static::SUCCESS;
    }
}
