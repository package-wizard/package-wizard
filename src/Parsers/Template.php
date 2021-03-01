<?php

namespace Helldar\PackageWizard\Parsers;

final class Template
{
    protected $type;

    protected $require = [];

    protected $require_dev = [];

    protected $minimum_stability = 'stable';

    protected $steps = [];

    public function parse(string $filename): self
    {
        extract($this->load($filename), EXTR_SKIP);

        $this->type        = $type ?? 'library';
        $this->require     = $require ?? [];
        $this->require_dev = $require_dev ?? [];
        $this->steps       = $steps ?? [];

        $this->minimum_stability = $minimumStability ?? 'stable';

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRequire(): array
    {
        return $this->require;
    }

    public function getRequireDev(): array
    {
        return $this->require_dev;
    }

    public function getMinimumStability(): string
    {
        return $this->minimum_stability;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    protected function load(string $filename): array
    {
        return json_decode(file_get_contents($filename));
    }
}
