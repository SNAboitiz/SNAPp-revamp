<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadBillRequest;
use App\Models\Bill;
use App\Models\Customer;
use App\Services\BillingService;
use App\Services\OracleInvoiceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    public function __construct(
        protected BillingService $billingService,
        protected OracleInvoiceService $oracleInvoiceService
    ) {}

    public function showBillsPage(Request $request)
    {
        $billsPaginator = $this->billingService->getPaginatedBillsForUser(Auth::user(), $request);
        $customers = Customer::orderBy('account_name')->get();

        // Extract unique facilities from paginated bills
        $facilities = collect($billsPaginator->items())
            ->pluck('Facility')
            ->filter(fn ($value) => $value !== 'N/A' && ! empty($value))
            ->unique()
            ->sort()
            ->values();

        return view('my-bills', [
            'bills' => $billsPaginator,
            'payments' => null,
            'activeTab' => 'bills',
            'customers' => $customers,
            'facilities' => $facilities,
        ]);
    }

    public function showPaymentHistory(Request $request)
    {
        $paymentsPaginator = $this->billingService->getPaginatedPaymentHistoryForUser(Auth::user(), $request);
        $customers = customer::orderBy('account_name')->get();

        return view('my-bills', [
            'payments' => $paymentsPaginator,
            'bills' => null,
            'activeTab' => 'payments',
            'customers' => $customers,
        ]);
    }

    public function showManageBillsPage(Request $request)
    {
        // Delegate bill retrieval to the BillingService
        $bills = $this->billingService->getPaginatedUploadedBills($request);

        // Load customers as before for filtering or display
        $customers = Customer::orderBy('account_name')->get();

        return view('admin.bills.bill-card', compact('bills', 'customers'));
    }

    public function uploadBills(UploadBillRequest $request)
    {
        $user = Auth::user();
        $customer = Customer::where('id', $request->customer_id)->firstOrFail();

        // Format billing period as "26-APR-23 to 25-MAY-23"
        $start = Carbon::parse($request->billing_start_date)->format('d-M-y');
        $end = Carbon::parse($request->billing_end_date)->format('d-M-y');
        $billingPeriod = strtoupper("{$start} to {$end}");

        // Format filename using short_name, billing period, and bill number
        $filename = "{$customer->short_name}_{$billingPeriod}_{$request->bill_number}.pdf";

        // Store the file
        $path = $request->file('file_path')->storeAs('snapp_bills', $filename, config('filesystems.default'));

        // Create the bill record

        Bill::create([
            'customer_id' => $request->customer_id,
            'billing_start_date' => $request->billing_start_date,
            'billing_end_date' => $request->billing_end_date,
            'billing_period' => $billingPeriod,
            'bill_number' => $request->bill_number,
            'file_path' => $path,
            'uploaded_by' => $user->id,
        ]);

        return redirect()->route('bills.manage')->with('success', 'Bill uploaded successfully.');
    }
}
