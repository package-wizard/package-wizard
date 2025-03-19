<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers\Questions;

use PackageWizard\Installer\Data\AuthorData;
use PackageWizard\Installer\Data\Questions\QuestionAuthorData;
use PackageWizard\Installer\Fillers\Filler;
use PackageWizard\Installer\Services\GitService;
use Spatie\LaravelData\Data;

use function Laravel\Prompts\text;

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

    public function get(): AuthorData
    {
        return AuthorData::from([
            'id'      => $this->data->id,
            'replace' => $this->data->replace,
            'format'  => $this->data->format,
            'name'    => $this->name(),
            'email'   => $this->email(),
        ]);
    }

    protected function name(): string
    {
        if ($name = $this->data->author->name) {
            return $name;
        }

        return text(
            label   : __('What is your name?'),
            default : (string) $this->git->userName(),
            required: true
        );
    }

    protected function email(): string
    {
        if ($email = $this->data->author->email) {
            return $email;
        }

        return text(
            label   : __('What is your email?'),
            default: (string) $this->git->userEmail(),
        );
    }
}
