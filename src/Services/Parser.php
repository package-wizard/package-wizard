<?php

namespace Helldar\PackageWizard\Services;

use Helldar\Support\Concerns\Makeable;

final class Parser
{
    use Makeable;

    /** @var string */
    protected string $template;

    public function template(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function replace(string $key, string $value): self
    {
        $this->template = str_replace('{{' . $key . '}}', $value, $this->template);

        return $this;
    }

    public function replacesMany(array $items): self
    {
        foreach ($items as $key => $value) {
            $this->replace($key, $value);
        }

        return $this;
    }

    public function get(): string
    {
        return $this->template;
    }
}
