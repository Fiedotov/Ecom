<?php

namespace App\Salesforce\Api;

use Carbon\CarbonInterval;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GenerateApiToken
{
    protected string $token;
    protected const CACHE_KEY = 'salesforce_token';

    public function __invoke(): ?string
    {
        if (Cache::has(self::CACHE_KEY)) {
            return Cache::get(self::CACHE_KEY);
        }

        $response = Http::contentType('application/x-www-form-urlencoded')
            ->send('POST', sprintf('%s/oauth2/token', config('salesforce.services_base_url')), [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => config('salesforce.client_id'),
                    'client_secret' => config('salesforce.client_secret'),
                ]
            ]);

        throw_if(
            $response->status() === 400,
            new Exception("Salesforce error. {$response->json('error')} / {$response->json('error_description')}")
        );

        $this->token = $response->json('access_token');

        Cache::put(self::CACHE_KEY, $this->token, CarbonInterval::hour());

        return $this->token;
    }
}
