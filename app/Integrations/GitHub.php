<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Integrations;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use PackageWizard\Installer\Data\GitHub\LicenseData;

use function config;

class GitHub
{
    /**
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Illuminate\Http\Client\RequestException
     *
     * @return Collection<LicenseData>
     */
    public function licenses(): Collection
    {
        return $this->client()
            ->get('/licenses')
            ->throw()
            ->collect()
            ->map(static fn (array $item) => LicenseData::from($item));
    }

    public function license(string $spdxId): LicenseData
    {
        $data = $this->client()
            ->get('/licenses/' . $spdxId)
            ->throw()
            ->json();

        return LicenseData::from($data);
    }

    protected function client(): PendingRequest
    {
        return Http::accept('application/vnd.github+json')
            ->asJson()
            ->baseUrl('https://api.github.com')
            ->withHeader('X-GitHub-Api-Version', '2022-11-28')
            ->when(
                config('github.token'),
                fn (PendingRequest $request, string $token) => $request
                    ->withHeader('Authorization', 'Bearer ' . $token)
            )
            ->throw();
    }
}
