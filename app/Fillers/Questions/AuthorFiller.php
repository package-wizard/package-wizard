<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers\Questions;

use PackageWizard\Installer\Data\AuthorData;
use PackageWizard\Installer\Data\Questions\QuestionAuthorData;
use PackageWizard\Installer\Data\ReplaceData;
use PackageWizard\Installer\Fillers\Filler;
use PackageWizard\Installer\Services\GitService;
use Spatie\LaravelData\Data;

use function Laravel\Prompts\text;
use function str_replace;

/** @method static make(QuestionAuthorData|Data $data) */
class AuthorFiller extends Filler
{
    public function __construct(
        protected QuestionAuthorData $data,
        protected GitService $git,
    ) {
        if (! isset($this->data->author)) {
            $this->data->author = AuthorData::from();
        }
    }

    public function get(): ReplaceData
    {
        return ReplaceData::from([
            'replace' => $this->data->replace,
            'with'    => $this->answer(),
        ]);
    }

    protected function answer(): string
    {
        return str_replace(
            [':name:', ':email:'],
            [$this->name(), $this->email()],
            $this->data->format
        );
    }

    protected function name(): string
    {
        if ($name = $this->data->author->name) {
            return $name;
        }

        return text(
            label   : 'What is your name?',
            default : $this->git->userName(),
            required: true
        );
    }

    protected function email(): string
    {
        if ($email = $this->data->author->email) {
            return $email;
        }

        return text(
            label  : 'Enter your email address:',
            default: $this->git->userEmail(),
        );
    }
}
