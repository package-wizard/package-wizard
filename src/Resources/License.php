<?php

namespace Helldar\PackageWizard\Resources;

use Helldar\Support\Concerns\Makeable;

final class License extends BaseResource
{
    use Makeable;

    protected $license;

    protected $authors = [];

    public function license(string $license): self
    {
        $this->license = $license;

        return $this;
    }

    public function authors(array $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    public function toString(): string
    {
        return $this->parser
            ->template($this->load())
            ->replace('year', date('Y'))
            ->replace('authors', implode(', ', $this->authors))
            ->get();
    }

    protected function path(): string
    {
        return realpath(__DIR__ . '/../../resources/licenses/' . $this->license);
    }
}
