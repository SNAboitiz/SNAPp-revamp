<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KissflowService
{
    /**
     * The HTTP client instance.
     *
     * @var \Illuminate\Http\Client\PendingRequest
     */
    private $http;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->http = Http::baseUrl(config('services.kissflow.url'))
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);
    }

    /**
     * Submit an inquiry to Kissflow.
     *
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function submitInquiry(array $data)
    {
        try {
            $url = '/integration/2/AcA53QvP3wEAB/webhook/t66eMnaoEDZDs8qZyRSo8LKODoRoiM3H2E6Dx-3FzWQZkXO1PUENv1QLp2oZcli3TdJBQv5q-m8LZZmi-UQ';

            $response = $this->http->post($url, $data);

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
