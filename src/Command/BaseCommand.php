<?php

namespace Helldar\PackageWizard\Command;

use Composer\Command\BaseCommand as ComposerBaseCommand;
use Helldar\PackageWizard\Concerns\Git;
use Helldar\PackageWizard\Concerns\Output;
use Helldar\PackageWizard\Concerns\Questionable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends ComposerBaseCommand
{
    use Git;
    use Output;
    use Questionable;

    /** @var \Symfony\Component\Console\Input\InputInterface */
    protected InputInterface $input;

    /** @var \Symfony\Component\Console\Output\OutputInterface */
    protected OutputInterface $output;

    abstract public function handle();

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = $output;

        $this->handle();
    }
}
