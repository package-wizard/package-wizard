<?php

namespace Helldar\PackageWizard\Command;

use Composer\Command\BaseCommand as ComposerBaseCommand;
use Helldar\PackageWizard\Concerns\Input;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends ComposerBaseCommand
{
    use Input;

    /** @var \Symfony\Component\Console\Input\InputInterface */
    protected $input;

    /** @var \Symfony\Component\Console\Output\OutputInterface */
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
