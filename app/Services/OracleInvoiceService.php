<?php

namespace App\Services;

use DateTime;
use Exception; // Import DateTime class for date parsing
use Illuminate\Support\Facades\Http; // Import Exception for more specific error handling

class OracleInvoiceService
{
    private $apiHeaders;

    private $apiAuth;

    private $baseUrl;

    public function __construct()
    {
        $this->apiHeaders = [
            'Content-Type' => 'application/vnd.oracle.adf.resourcecollection+json',
            'Accept' => 'application/json',
        ];

        // BEST PRACTICE: Use the config() helper, not env()
        $this->apiAuth = [
            config('services.oracle.username'),
            config('services.oracle.password'),
        ];

        $this->baseUrl = config('services.oracle.url');

        // You can add a check to fail early if credentials are not set
        if (empty($this->apiAuth[0]) || empty($this->apiAuth[1])) {
            // In a real app, you'd throw a specific exception here.
            throw new Exception('Oracle API credentials are not configured.');
        }
    }

    /**
     * Fetches invoice data and consistently sorts it by the latest billing period (descending).
     * This ensures the most recent invoice is always at index [0] of the returned array.
     *
     * @param string $customerNumber
     * @return array
     */
       public function fetchInvoiceData($customerNumber)
    {
        $url = $this->baseUrl.'receivablesInvoices';

        $queryParams = [
            'finder' => "invoiceSearch;TransactionSource=SNAP AUTOINVOICE,TransactionType=TRADE-RES,BusinessUnit=SNAPR BU,BillToCustomerNumber={$customerNumber}",
            'totalResults' => 'true'
            // No 'orderBy' parameter, as we are doing the reliable chronological sorting in PHP.
        ];

        $response = Http::withBasicAuth(...$this->apiAuth)
            ->withHeaders($this->apiHeaders)
            ->get($url, $queryParams);

        if ($response->successful()) {
            $items = $response->json()['items'] ?? [];

            // --- Apply reliable chronological sorting (newest first) ---
            usort($items, function ($a, $b) {
                $commentsA = $a['Comments'] ?? '';
                $commentsB = $b['Comments'] ?? '';

                $partsA = explode(' to ', $commentsA);
                $partsB = explode(' to ', $commentsB);

                $endDateStrA = $partsA[1] ?? null;
                $endDateStrB = $partsB[1] ?? null;

                $dateA = $endDateStrA ? DateTime::createFromFormat('d-M-y', $endDateStrA) : false;
                $dateB = $endDateStrB ? DateTime::createFromFormat('d-M-y', $endDateStrB) : false;

                if ($dateA === false && $dateB === false) {
                    return 0;
                }
                if ($dateA === false) {
                    return 1;
                }
                if ($dateB === false) {
                    return -1;
                }

                return $dateB->getTimestamp() - $dateA->getTimestamp();
            });

            // Cut off to only the top 5 most recent items ---
            $limitedItems = array_slice($items, 0, 5);

            // You can add a dd() here temporarily to verify the final list:
            // dd($limitedItems);

            return $limitedItems; // Return the sorted and limited list of invoices
        }

        return []; 
    }

    public function fetchConsumption($transactionId)
    {
        // Use the base URL from the config
        $url = "{$this->baseUrl}receivablesInvoices/{$transactionId}/child/receivablesInvoiceLines";

        $response = Http::withBasicAuth(...$this->apiAuth)
            ->withHeaders($this->apiHeaders)
            ->get($url);

        if ($response->successful()) {
            $items = $response->json()['items'] ?? [];

            return $items[0]['Quantity'] ?? 0;
        }

        return 0;
    }
}
