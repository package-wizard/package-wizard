<?php

namespace Helldar\PackageWizard\Command;

use Composer\Command\BaseCommand as ComposerBaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends ComposerBaseCommand
{
    protected $input;

    protected $output;

    abstract public function handle();

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = $output;

        $this->handle();
    }

    protected function basePath(): string
    {
        return $this->getComposer()->getConfig()->get('data-dir');
    }
}
