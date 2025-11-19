<?php

namespace App\Observers;

use App\Models\Inquiry;
use App\Services\KissflowService;

class InquiryObserver
{
    /**
     * Handle the Inquiry "created" event.
     */
    public function created(Inquiry $inquiry): void
    {
        $params = [
            'message' => $inquiry->message,
            'type' => $inquiry->type,
            'email' => $inquiry->user->email,
            'name' => $inquiry->user->name,
        ];

        $response = (new KissflowService)->submitInquiry($params);

        $response = json_decode($response->body(), true);

        $inquiry->update([
            'kissflow_id' => $response['id'] ?? null,
        ]);
    }
}
