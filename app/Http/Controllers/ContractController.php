<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Facility;
use App\Services\ContractService;
use Illuminate\Support\Carbon;

class ContractController extends Controller
{
    public function __construct(protected ContractService $contractService) {}

    public function showContractsPage()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {

            // Admin sees ALL contracts
            $contractsQuery = Contract::with(['customer', 'facility'])
                ->orderBy('created_at', 'desc')
                ->paginate(5);

        } else {

            // Customer-side filtering using USER TABLE ONLY
            $customerId = $user->customer_id;
            $facilityId = $user->facility_id; // optional

            if ($customerId) {

                $query = Contract::with(['customer', 'facility'])
                    ->where('customer_id', $customerId);

                // If user has facility assigned, filter by it
                if ($facilityId) {
                    $query->where('facility_id', $facilityId);
                }

                $contractsQuery = $query
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

            } else {
                // User has no assigned customer → return empty paginator
                $contractsQuery = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5);
            }
        }

        // Transform contracts
        $contracts = $contractsQuery;
        $contracts->getCollection()->transform(function ($contract) {
            $status = 'unknown';
            if ($contract->contract_end) {
                $endDate = Carbon::parse($contract->contract_end);
                $status = $endDate->isPast() ? 'Expired' : 'Active';
            }

            return [
                'contract_name' => $contract->description,
                'customer_name' => $contract->customer->account_name ?? 'N/A',
                'facility_name' => $contract->facility->name ?? 'N/A',
                'facility_sein' => $contract->facility->sein ?? null,
                'contract_period' => $contract->contract_period,
                'contract_end' => $contract->contract_end,
                'upload_date' => $contract->created_at->format('d-M-Y'),
                'status' => $status,
                'gcsPdfUrl' => $this->contractService->getFileUrl($contract->document),
            ];
        });

        // Build customer/facility lists for form (ALSO FIXED TO USE USER FIELDS)
        if ($user->hasRole('admin')) {

            $customers = Customer::with('facilities')->orderBy('account_name')->get();
            $facilities = Facility::orderBy('name')->get();

        } else {

            $customerId = $user->customer_id;
            $facilityId = $user->facility_id;

            if ($customerId) {

                // User belongs to a customer, show only that customer's data
                $customers = Customer::with('facilities')
                    ->where('id', $customerId)
                    ->get();

                if ($facilityId) {
                    // User restricted to one facility
                    $facilities = Facility::where('id', $facilityId)->get();
                } else {
                    // User can see all facilities under the customer
                    $facilities = Facility::where('customer_id', $customerId)
                        ->orderBy('name')
                        ->get();
                }

            } else {
                $customers = collect();
                $facilities = collect();
            }
        }

        return view('my-contracts', compact('contracts', 'customers', 'facilities'));
    }

    public function store(StoreContractRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        $customer = Customer::find($validated['customer_id']);

        // Facility is optional
        $facilityId = $validated['facility_id'] ?? null;
        $facility = $facilityId ? Facility::find($facilityId) : null;

        if (! $customer) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid customer selected.');
        }

        $shortname = $customer->short_name;
        $sein = $facility?->sein ?? ''; // default if no facility selected

        $start = Carbon::parse($validated['contract_start']);
        $end = Carbon::parse($validated['contract_end']);
        $period = strtoupper($start->format('d-M-y').' to '.$end->format('d-M-y'));

        $extension = $request->file('document')->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $filename = "CONTRACT_{$shortname}_{$sein}_{$period}_{$timestamp}.{$extension}";

        // Upload file using service
        $path = $this->contractService->uploadFile($request->file('document'), $filename);

        // Save to database
        Contract::create([
            'customer_id' => $customer->id,
            'facility_id' => $facility?->id, // allow null
            'description' => $validated['description'],
            'contract_start' => $validated['contract_start'],
            'contract_end' => $validated['contract_end'],
            'contract_period' => $period,
            'document' => $path,
            'status' => 1,
            'created_by' => $user->id,
        ]);

        return redirect()->route('my-contracts')->with('success', 'Contract uploaded successfully.');
    }
}
