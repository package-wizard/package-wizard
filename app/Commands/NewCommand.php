<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Commands;

use Illuminate\Support\Composer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NewCommand extends Command
{
    protected Composer $composer;

    protected function configure(): void
    {
        $this
            ->setName('new')
            ->setDescription('Create new project')
            ->addArgument('name', InputArgument::OPTIONAL, default: 'list')
            ->addOption('local', null, InputOption::VALUE_NONE, 'Set up a local project in the specified folder');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return static::SUCCESS;
    }
}
