<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Integrations;

use Illuminate\Support\Facades\Http;

use function blank;
use function trim;

class Packagist
{
    public function search(string $name): array
    {
        if (blank($name)) {
            return [];
        }

        return Http::acceptJson()
            ->asJson()
            ->throw()
            ->get('https://packagist.org/search.json', [
                'q' => trim($name),
            ])
            ->throw()
            ->collect('results')
            ->pluck('name')
            ->all();
    }
}
