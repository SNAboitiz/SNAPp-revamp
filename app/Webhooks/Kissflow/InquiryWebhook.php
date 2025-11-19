<?php

namespace App\Webhooks\Kissflow;

use App\Models\Inquiry;
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

        $status = $data['payload']['status'];
        if (empty($status)) {
            throw new \Exception('Status is missing in the webhook payload.');
        }

        $id = $data['payload']['kissflow_id'];
        if (empty($id)) {
            throw new \Exception('ID is missing in the webhook payload.');
        }

        Inquiry::whereKissflowId($id)->update(['status' => $status]);

        http_response_code(200);
    }
}
