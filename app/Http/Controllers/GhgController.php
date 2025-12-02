<?php

namespace App\Http\Controllers;

use App\Services\OracleInvoiceService;
use Illuminate\Support\Facades\Auth;

class GhgController extends Controller
{
    protected $oracleInvoiceService;

    public function __construct(OracleInvoiceService $oracleInvoiceService)
    {
        $this->oracleInvoiceService = $oracleInvoiceService;
    }

    public function calculateEmissions()
    {
        $user = Auth::user();
        $accountName = $user->customer?->account_name;
        $customerId = $user->customer?->customer_number;

        $invoiceData = $this->oracleInvoiceService->fetchInvoiceData($customerId);
        if (empty($invoiceData)) {
            return response()->json(['error' => 'No invoices found for this customer'], 404);
        }

        $latestInvoice = $invoiceData[0];
        $transactionId = $latestInvoice['CustomerTransactionId'] ?? null;

        if (! $transactionId) {
            return response()->json(['error' => 'Invalid invoice data'], 400);
        }
        $consumption = $this->oracleInvoiceService->fetchConsumption($transactionId);

        $ERF = 0.709;
        $BulbReplacementFactor = 37.2;
        $SequestrationRate = 60;
        $TrashBagConversionFactor = 23.1;

        $avoidedEmissions = $consumption * $ERF;
        $bulbReplacement = $consumption / $BulbReplacementFactor;
        $treesGrown = $avoidedEmissions / $SequestrationRate;
        $trashBagsRecycled = $avoidedEmissions / $TrashBagConversionFactor;

        return view('energy-consumption', [
            'consumption' => number_format($consumption, 2),
            'avoidedEmissions' => number_format($avoidedEmissions, 2),
            'bulbReplacement' => number_format($bulbReplacement, 2),
            'treesGrown' => number_format($treesGrown, 2),
            'trashBagsRecycled' => number_format($trashBagsRecycled, 2),
            'accountName' => $accountName,
        ]);
    }
}
