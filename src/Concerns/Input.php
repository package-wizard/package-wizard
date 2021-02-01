<?php

namespace Helldar\PackageWizard\Concerns;

use Helldar\PackageWizard\Contracts\Stepable;
use Helldar\PackageWizard\Services\Output;
use Helldar\PackageWizard\Steps\Arr;
use Helldar\PackageWizard\Steps\Boolean;
use Helldar\PackageWizard\Steps\Choice;
use Helldar\PackageWizard\Steps\KeyValue;
use Helldar\PackageWizard\Steps\Text;
use Helldar\PackageWizard\Steps\Url;

/** @mixin \Helldar\PackageWizard\Command\BaseCommand */
trait Input
{
    public function inputText(string $question): Stepable
    {
        return Text::make($this->getIO(), $this->output())->question($question);
    }

    public function inputChoice(string $question, array $choices, $default): Stepable
    {
        return Choice::make($this->getIO(), $this->output())
            ->question($question)
            ->choices($choices)
            ->back($default);
    }

    public function inputArray(string $question): Stepable
    {
        return Arr::make($this->getIO(), $this->output())->question($question);
    }

    public function inputKeyValue(string $question, array $values): Stepable
    {
        return KeyValue::make($this->getIO(), $this->output())->question($question)->values($values);
    }

    public function inputUrl(string $question): Stepable
    {
        return Url::make($this->getIO(), $this->output())->question($question);
    }

    public function inputBoolean(string $question): Stepable
    {
        return Boolean::make($this->getIO(), $this->output())->question($question);
    }

    protected function output(): Output
    {
        return Output::make($this->getIO(), $this->output);
    }
}
