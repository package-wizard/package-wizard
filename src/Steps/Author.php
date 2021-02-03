<?php

namespace Helldar\PackageWizard\Steps;

use InvalidArgumentException;

final class Author extends BaseStep
{
    protected string $question = 'Author of package (<comment>name</comment> <<comment>email</comment>>)';

    protected function input(): ?array
    {
        if ($author = $this->author()) {
            return $author;
        }

        return $this->ask();
    }

    protected function ask()
    {
        return $this->io->askAndValidate($this->question(), fn($value) => $this->parseAuthorString($value));
    }

    protected function author(): ?array
    {
        $name  = $this->name();
        $email = $this->email();

        return ! empty($name) && ! empty($email) ? compact('name', 'email') : null;
    }

    protected function name(): ?string
    {
        return $this->getValue('COMPOSER_DEFAULT_AUTHOR', 'user.name');
    }

    protected function email(): ?string
    {
        return $this->getValue('COMPOSER_DEFAULT_EMAIL', 'user.email');
    }

    protected function getValue(string $server, string $git): ?string
    {
        switch (true) {
            case ! empty($_SERVER[$server]):
                return $_SERVER[$server];

            case isset($this->git[$git]):
                return $this->git[$git];

            default:
                return null;
        }
    }

    protected function parseAuthorString($author): array
    {
        if (preg_match('/^(?P<name>[- .,\p{L}\p{N}\p{Mn}\'’"()]+) <(?P<email>.+?)>$/u', $author, $match)) {
            if ($this->isValidEmail($match['email'])) {
                return [
                    'name'  => trim($match['name']),
                    'email' => $match['email'],
                ];
            }
        }

        throw new InvalidArgumentException(
            'Invalid author string.  Must be in the format: ' .
            'John Smith <john@example.com>'
        );
    }

    protected function isValidEmail($email): bool
    {
        return false !== filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
