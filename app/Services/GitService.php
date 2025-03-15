<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

class GitService
{
    public function __construct(
        protected ProcessService $process
    ) {}

    public function userName(): string
    {
        return $this->process->runWithOutput(['git', 'config', '--global', 'user.name']);
    }

    public function userEmail(): string
    {
        return $this->process->runWithOutput(['git', 'config', '--global', 'user.email']);
    }
}
