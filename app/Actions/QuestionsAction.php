<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions;

use PackageWizard\Installer\Data\ConditionData;
use PackageWizard\Installer\Data\Questions\QuestionData;
use PackageWizard\Installer\Data\ReplaceData;
use PackageWizard\Installer\Enums\ConditionOperatorEnum;
use PackageWizard\Installer\Enums\TypeEnum;
use PackageWizard\Installer\Fillers\AskFiller;
use PackageWizard\Installer\Fillers\Questions\AuthorFiller;
use PackageWizard\Installer\Fillers\Questions\LicenseFiller;
use PackageWizard\Installer\Replacers\AskReplacer;
use PackageWizard\Installer\Replacers\AuthorReplacer;
use PackageWizard\Installer\Replacers\LicenseReplacer;
use PackageWizard\Installer\Services\ComparatorService;

class QuestionsAction extends Action
{
    protected bool $rawOutput = true;

    protected ?ComparatorService $comparator = null;

    protected function perform(): void
    {
        $this->config()->questions->each(function (QuestionData $question, int $index) {
            static::verboseWriteln('    Question index: ' . $index);

            $this->question($question);
        });
    }

    protected function question(QuestionData $question): void
    {
        if ($this->skip($question)) {
            return;
        }

        if ($value = $this->getValue($question)) {
            $this->config()->replaces->push($value);
        }
    }

    protected function getValue(QuestionData $question): ?ReplaceData
    {
        return match ($question->type) {
            TypeEnum::Ask     => AskReplacer::get(AskFiller::make(data: $question), true),
            TypeEnum::Author  => AuthorReplacer::get(AuthorFiller::make(data: $question), true),
            TypeEnum::License => LicenseReplacer::get(LicenseFiller::make(data: $question), true),
        };
    }

    protected function skip(QuestionData $question): bool
    {
        if ($question->condition === true) {
            return false;
        }

        if ($this->forPath($question->condition)) {
            return $this->comparator()->disallow(
                $question->condition->operator,
                $this->config()->directory . '/' . $question->condition->value,
            );
        }

        return $this->comparator()->disallow(
            $question->condition->operator,
            $question->condition->value,
            $this->find($question->condition->for)->with,
        );
    }

    protected function find(string $id): ReplaceData
    {
        return $this->config()->replaces
            ->where('id', $id)
            ->firstOrFail();
    }

    protected function forPath(ConditionData $condition): bool
    {
        return $condition->operator === ConditionOperatorEnum::PathExists
            || $condition->operator === ConditionOperatorEnum::PathDoesNotExist;
    }

    protected function comparator(): ComparatorService
    {
        return $this->comparator ??= new ComparatorService();
    }
}
