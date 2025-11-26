<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\OracleInvoiceService;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class OracleInvoiceFetchTest extends TestCase
{
    /** @test */
    public function it_times_oracle_invoice_fetch_and_logs_duration()
    {
        $user = User::whereNotNull('customer_id')->first();
        $this->assertNotNull($user, 'No user with customer_id found in database.');

        $this->be($user);

        $oracle = app(OracleInvoiceService::class);

        $start = microtime(true);
        $items = $oracle->fetchInvoiceData($user->customer_id);
        $end = microtime(true);

        $duration = round($end - $start, 3);
        Log::info("⏱️ Oracle Invoice Fetch Time: {$duration} seconds");

        $this->assertIsArray($items, 'Expected an array from Oracle, got: '.gettype($items));
        $this->assertLessThan(15, $duration, "API took too long: {$duration}s");
    }
}
