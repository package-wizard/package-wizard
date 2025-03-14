<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use PackageWizard\Installer\Concerns\InteractsWithNew;
use PackageWizard\Installer\Fillers\ProjectNameFiller;
use PackageWizard\Installer\Fillers\ProjectPathFiller;
use PackageWizard\Installer\Services\HttpService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class NewCommand extends Command implements PromptsForMissingInput
{
    use InteractsWithNew;

    protected $signature = 'new';

    protected $description = 'Create new project';

    public function handle(HttpService $http): int
    {
        dd(
            $this->repositoryPath($http)
        );
        // Step 1
        // TODO: Ask for project
        // TODO: Download project or use local
        /*
         * Если запуск с параметром --local, то проверять существование папки иначе эксепшен.
         * Если ищем репу, то кидать запрос на существование урла https://repo.packagist.org/p2/laravel-lang/publisher.json и, если репы нет, кидать эксепшен.
         */

        // Step 2
        // TODO: Read wizard.json file and validate schema
        // TODO: Fill MainData class

        return static::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED)
            ->addArgument('path', InputArgument::REQUIRED, 'The path to the folder for downloading the project')
            ->addOption('dev', null, InputOption::VALUE_OPTIONAL, 'Install the latest "development" release')
            ->addOption('local', null, InputOption::VALUE_NONE, 'Set up a local project in the specified folder');
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => fn () => ProjectNameFiller::make(
                local: $this->option('local')
            ),

            'path' => fn () => ProjectPathFiller::make(
                name: $this->argument('name')
            ),
        ];
    }
}
