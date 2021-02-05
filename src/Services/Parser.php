<?php

namespace Helldar\PackageWizard\Services;

use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Filesystem\File;

final class Parser
{
    use Makeable;

    /** @var string */
    protected string $template;

    public function template(string $path): self
    {
        File::validate($path);

        $this->template = file_get_contents($path);

        return $this;
    }

    public function replace(string $key, string $value): self
    {
        $this->template = str_replace('{{' . $key . '}}', $value, $this->template);

        return $this;
    }

    public function get(): string
    {
        return $this->template;
    }
}
