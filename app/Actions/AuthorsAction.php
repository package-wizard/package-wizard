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
            static::verboseWriteln(__('info.index_number', ['name' => 'author', 'index' => $index]), 4);

            $this->author($author);
        });
    }

    protected function steps(): int
    {
        return $this->config()->authors->count();
    }

    protected function author(AuthorData|Data $author): void
    {
        $author = AuthorReplacer::get($author);

        $this->config()->replaces->push($author);
    }
}
