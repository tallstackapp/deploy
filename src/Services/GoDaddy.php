<?php

namespace TallStackApp\Deploy\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class GoDaddy
{
    public function __construct()
    {
        $apiKey = env('GODADDY_API_KEY');
        $apiSecret = env('GODADDY_API_SECRET');

        $this->client = Http::withHeaders([
            'Authorization' => "sso-key {$apiKey}:{$apiSecret}"
        ]);
    }

    public function domains(): Response
    {
        return $this->client->get(
            $this->endpoint('domains')
        );
    }

    public function domainDnsRecordsCreate(string $domain, string $name, string $type, array $records)
    {
        /*
        $records = [
          [
            'type'     => 'A',
            'data'     => '127.0.0.1',
            'ttl'      => 0,
            'priority' => 0,
            'port'     => 65535,
            'protocol' => 'string',
            'service'  => 'string',
            'weight'   => 0
          ]
        ];
        */
        return $this->client->post(
            $this->endpoint("domains/{$domain}/records", [
                'records' => $records
            ])
        );
    }

    public function domainDnsRecordsUpdate(string $domain, string $name, string $type, array $records)
    {
        /*
        $records = [
          [
            'data'     => '127.0.0.1',
            'ttl'      => 0,
            'priority' => 0,
            'port'     => 65535,
            'protocol' => "string",
            'service'  => "string",
            'weight'   => 0
          ]
        ];
        */
        return $this->client->put(
            $this->endpoint("domains/{$domain}/records/{$type}/{$name}", [
                'records' => $records
            ])
        );
    }

    public function domainUpdateNameservers(string $domain, array $nameservers)
    {
        $records = collect($nameservers)->map(fn ($nameserver) => [
            'data'     => $nameserver,
            // 'priority' => 0,
            // 'port'     => 65535,
            // 'protocol' => "string",
            // 'service'  => "string",
            // 'ttl'      => 0,
            // 'weight'   => 0
        ])->all();

        return $this->domainDnsRecordsUpdate(
            domain: $domain,
            name: '@',
            type: 'ns',
            records: $records
        );
    }

    private function endpoint(string $suffix): string
    {
        return "https://api.godaddy.com/v1/{$suffix}";
    }
}