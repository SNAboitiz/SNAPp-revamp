<?php

namespace App\Webhooks\Kissflow;

use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class InquiryWebhook extends ProcessWebhookJob
{
    /**
     * Create a new job instance.
     *
     * @param  string  $webhookCall
     * @return void
     */
    public function __construct($webhookCall)
    {
        parent::__construct($webhookCall);
    }

    /**
     * Handle the incoming webhook call.
     *
     * @return void
     */
    public function handle()
    {
        $data = json_decode($this->webhookCall, true);

        // TODO: save status

        http_response_code(200);
    }
}
