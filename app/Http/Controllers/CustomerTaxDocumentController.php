<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerTaxDocumentRequest;
use App\Models\Customer;
use App\Models\CustomerTaxDocument;
use App\Models\Facility;

class CustomerTaxDocumentController extends Controller
{
    public function store(StoreCustomerTaxDocumentRequest $request, Customer $customer, ?Facility $facility = null)
    {
        $validated = $request->validated();

        $ext = $request->file('file')->getClientOriginalExtension();

        $sein = $facility ? $facility->sein : 'NOFAC';
        $filename = "{$validated['document_number']}_{$customer->short_name}_{$sein}.{$ext}";

        $path = $request->file('file')->storeAs(
            'snapp_tax_docs',
            $filename,
            config('filesystems.default')
        );

        CustomerTaxDocument::create([
            'customer_id' => $customer->id,
            'facility_id' => $facility?->id,
            'document_number' => $validated['document_number'],
            'file_path' => $path,
        ]);

        return redirect()->back()->with('success', '2307 document uploaded successfully.');
    }
}
