<?php

namespace Helldar\PackageWizard\Steps;

use Composer\IO\IOInterface;
use Helldar\PackageWizard\Concerns\IO;
use Helldar\PackageWizard\Concerns\Output;
use Helldar\PackageWizard\Contracts\Stepable;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Is;
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

    protected string $empty_for_skip = '<comment>leave blank to skip</comment>';

    protected bool $ask_many = false;

    protected bool $is_first = true;

    public function __construct(IOInterface $io, InputInterface $input, OutputInterface $output, array $git = [])
    {
        $this->io     = $io;
        $this->input  = $input;
        $this->output = $output;
        $this->git    = $git;
    }

    public function get()
    {
        if ($this->skip()) {
            return null;
        }

        $result = $this->ask_many ? $this->hasMany() : $this->hasOne();

        if ($this->isEmpty($result)) {
            $this->warning('The value cannot be empty.');

            return $this->get();
        }

        return $this->post($result);
    }

    abstract protected function input();

    protected function hasOne()
    {
        return $this->input();
    }

    protected function hasMany(array $result = []): array
    {
        while ($value = $this->hasOne()) {
            $result[] = $value;

            $this->is_first = false;
        }

        return $result;
    }

    protected function skip(): bool
    {
        return false;
    }

    protected function post($result)
    {
        return $result;
    }

    protected function question(string $question = null): string
    {
        $question = $this->getQuestionText($question ?: $this->question);

        if (Str::endsWith($question, ['?', '!'])) {
            return $question;
        }

        return Str::finish(trim($question, ':'), ': ');
    }

    protected function getQuestionText(string $question): string
    {
        return $this->ask_many && ! $this->is_first
            ? trim($question) . ' ' . $this->empty_for_skip
            : trim($question);
    }

    protected function isEmpty($value): bool
    {
        return Is::isEmpty($value);
    }
}
