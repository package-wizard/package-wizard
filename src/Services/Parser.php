<?php

namespace Helldar\PackageWizard\Services;

use Helldar\PackageWizard\Concerns\Logger;
use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Filesystem\File;

final class Parser
{
    use Logger;
    use Makeable;

    /** @var string */
    protected $template;

    /** @var string */
    protected $path;

    public function template(string $path): self
    {
        $this->path = $path;

        $this->log('Checking the existence of a file along the path:', $path);

        File::validate($path);

        $this->log('Reading the contents of a' . $path . 'file');

        $this->template = file_get_contents($path);

        return $this;
    }

    public function replace(string $key, string $value): self
    {
        $this->log('Replace the occurrence of "', $key, '" with "', $value, '" from the template int the "', $this->path, '" file');

        $this->template = str_replace('{{' . $key . '}}', $value, $this->template);

        return $this;
    }

    public function get(): string
    {
        $this->log('Getting a rendered template from "' . $this->path . '" file');

        return $this->template;
    }
}
