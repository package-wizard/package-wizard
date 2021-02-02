<?php

namespace Helldar\PackageWizard\Contracts;

interface Stepperable extends Arrayable
{
    public function steps(): array;

    public function setName(string $name): void;

    public function setDescription(string $description): void;

    public function setLicense(string $license): void;

    public function getLicense(): string;

    public function setKeywords(array $keywords): void;

    public function setAuthors(array $authors): void;

    public function pushAuthor(array $author): void;

    public function getAuthors(): array;

    public function setRequire(array $dependencies): void;

    public function setRequireDev(array $dev_dependencies): void;

    public function getAutoloadPath(): ?string;

    public function setRepositoryUrl(string $url): void;
}
