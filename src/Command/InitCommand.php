<?php

namespace Helldar\PackageWizard\Command;

use Composer\Command\InitCommand as BaseCommand;
use Composer\Repository\CompositeRepository;
use Composer\Repository\PlatformRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class InitCommand extends BaseCommand
{
    public function getDetermineRequirements(
        InputInterface $input,
        OutputInterface $output,
        string $preferred_stability = 'stable'
    ): array {
        return $this->determineRequirements($input, $output, [], $this->getPlatformRepo(), $preferred_stability);
    }

    protected function getPlatformRepo(): ?PlatformRepository
    {
        $repos = $this->getRepos();

        $platform_repo = null;

        if ($repos instanceof CompositeRepository) {
            foreach ($repos->getRepositories() as $repository) {
                if ($repository instanceof PlatformRepository) {
                    $platform_repo = $repository;
                    break;
                }
            }
        }

        return $platform_repo;
    }
}
