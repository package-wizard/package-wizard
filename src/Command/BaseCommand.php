<?php

namespace Helldar\PackageWizard\Command;

use Composer\Command\BaseCommand as ComposerBaseCommand;
use Helldar\PackageWizard\Concerns\Input;
use Helldar\PackageWizard\Concerns\Questionable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper\VarDumper;

abstract class BaseCommand extends ComposerBaseCommand
{
    use Input;
    use Questionable;

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
        VarDumper::dump('aaaa');
        $value = $this->getComposer(false)->getConfig()->get('data-dir');
        VarDumper::dump('bbb: ' . $value);

        return $value;
    }
}
