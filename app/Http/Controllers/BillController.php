<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadBillRequest;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Facility;
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
        if (auth()->user()->cant('can view bills')) {
            abort(403, 'Unauthorized action.');
        }

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
        if (auth()->user()->cant('can view bills')) {
            abort(403, 'Unauthorized action.');
        }

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
        $bills = $this->billingService->getPaginatedUploadedBills($request);

        $customers = Customer::orderBy('account_name')->get();

        // Load all facilities
        $facilities = Facility::orderBy('name')->get();

        return view('admin.bills.bill-card', compact('bills', 'customers', 'facilities'));
    }

    public function uploadBills(UploadBillRequest $request)
    {
        $user = Auth::user();

        // Use only validated fields
        $validated = $request->validated();

        // Find customer
        $customer = Customer::findOrFail($validated['customer_id']);

        // Find facility if provided
        $facility = ! empty($validated['facility_id']) ? Facility::find($validated['facility_id']) : null;

        // Format billing period
        $start = Carbon::parse($validated['billing_start_date'])->format('d-M-y');
        $end = Carbon::parse($validated['billing_end_date'])->format('d-M-y');
        $billingPeriod = strtoupper("{$start} to {$end}");

        // Build filename
        $ext = $request->file('file_path')->getClientOriginalExtension();
        $filename = "{$customer->short_name}_{$billingPeriod}_{$validated['bill_number']}.{$ext}";

        // Store file
        $path = $request->file('file_path')->storeAs('snapp_bills', $filename, config('filesystems.default'));

        // Prepare data to save
        $billData = [
            'customer_id' => $customer->id,
            'facility_id' => $facility?->id,
            'billing_start_date' => $validated['billing_start_date'],
            'billing_end_date' => $validated['billing_end_date'],
            'billing_period' => $billingPeriod,
            'bill_number' => $validated['bill_number'],
            'file_path' => $path,
            'uploaded_by' => $user->id,
        ];

        // Save bill
        $bill = Bill::create($billData);

        return redirect()->route('bills.manage')->with('success', 'Bill uploaded successfully.');
    }
}
