<?php

namespace Helldar\PackageWizard\Concerns;

use Composer\Util\ProcessExecutor;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

trait Git
{
    /** @var array|null */
    private $gitConfig;

    protected function getGitConfig(): array
    {
        if (null !== $this->gitConfig) {
            return $this->gitConfig;
        }

        $cmd = $this->cwd();
        $cmd->run();

        if ($cmd->isSuccessful()) {
            $this->gitConfig = [];

            preg_match_all('{^([^=]+)=(.*)$}m', $cmd->getOutput(), $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                $this->gitConfig[$match[1]] = $match[2];
            }

            return $this->gitConfig;
        }

        return $this->gitConfig = [];
    }

    protected function cwd(): Process
    {
        $git_bin = $this->pathFinder()->find('git');

        return new Process(sprintf('%s config -l', ProcessExecutor::escape($git_bin)));
    }

    protected function pathFinder(): ExecutableFinder
    {
        return new ExecutableFinder();
    }
}
