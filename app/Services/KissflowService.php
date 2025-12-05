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
     * @return \Illuminate\Http\Client\Response
     */
    public function submitInquiry(array $data)
    {
        try {
            $url = config('services.kissflow.inquiry_webhook');

            $response = $this->http->post($url, $data);

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
