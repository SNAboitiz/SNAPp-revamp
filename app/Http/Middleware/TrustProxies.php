<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class TrustProxies
{
    /**
     * The trusted proxies for this application.
     *
     * Set to '*' to trust all proxies. This is crucial for Cloud Run.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO | // This is the most critical header for Cloud Run
        Request::HEADER_X_FORWARDED_AWS_ELB; // Include if you ever use AWS ELB, otherwise it's fine
}
