<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions;

use PackageWizard\Installer\Data\AuthorData;
use PackageWizard\Installer\Replacers\AuthorReplacer;
use Spatie\LaravelData\Data;

class AuthorsAction extends Action
{
    protected function perform(): void
    {
        $this->config()->authors->each(function (AuthorData $author, int $index) {
            $this->verboseInfo('    Author index: ' . $index);

            $this->author($author);
        });
    }

    protected function author(AuthorData|Data $author): void
    {
        $author = AuthorReplacer::get($author);

        $this->config()->replaces->push($author);
    }

    protected function steps(): int
    {
        return $this->config()->authors->count();
    }
}
