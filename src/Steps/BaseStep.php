<?php

namespace Helldar\PackageWizard\Steps;

use Composer\IO\IOInterface;
use Helldar\PackageWizard\Concerns\IO;
use Helldar\PackageWizard\Concerns\Output;
use Helldar\PackageWizard\Contracts\Stepable;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Str;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseStep implements Stepable
{
    use IO;
    use Makeable;
    use Output;

    protected InputInterface $input;

    protected OutputInterface $output;

    protected array $git;

    protected string $question;

    protected string $question_package = 'Want to add another (Y/n)?';

    protected $result;

    protected bool $ask_many = false;

    public function __construct(IOInterface $io, InputInterface $input, OutputInterface $output, array $git = [])
    {
        $this->io     = $io;
        $this->input  = $input;
        $this->output = $output;
        $this->git    = $git;
    }

    abstract protected function input();

    public function question(string $question): Stepable
    {
        $question = trim($question);

        $ends_with = Str::endsWith($question, ['?', '!', ':', '.']);

        $suffix = $ends_with ? ' ' : ': ';

        $this->question = $question . $suffix;

        return $this;
    }

    public function get()
    {
        if ($this->ask_many && $this->getIO()->askConfirmation($this->question)) {
            return $this->getMany();
        }

        return $this->getOnce();
    }

    protected function getOnce()
    {
        return $this->input();
    }

    protected function getMany(): array
    {
        if (! is_array($this->result)) {
            $this->result = [];
        }

        do {
            $this->result[] = $this->getOnce();
        }
        while ($this->askAgain());

        return $this->result;
    }

    protected function askAgain(): bool
    {
        return $this->io->askConfirmation('Want to add another (Y/n)?', false);
    }
}
