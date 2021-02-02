<?php

namespace Helldar\PackageWizard\Contracts;

interface Stepperable extends Arrayable
{
    public function steps(): array;

    public function setName(string $name): void;

    public function setDescription(?string $description): void;

    public function setLicense(string $license): void;

    public function getLicense(): string;

    public function setKeywords(array $keywords): void;

    public function setAuthors(array $authors): void;

    public function getAuthors(): array;

    public function setRequire(array $require): void;

    public function setRequireDev(array $require_dev): void;

    public function setAutoload(): void;

    public function getAutoloadPath(): ?string;

    public function setExtra(string $key, $value): void;

    public function setRepositoryUrl(string $url): void;
}
