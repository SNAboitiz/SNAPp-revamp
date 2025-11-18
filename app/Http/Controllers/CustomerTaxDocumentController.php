<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerTaxDocumentRequest;
use App\Models\Customer;
use App\Models\Facility;
use App\Models\CustomerTaxDocument;
use Illuminate\Http\Request;

class CustomerTaxDocumentController extends Controller
{
    public function store(StoreCustomerTaxDocumentRequest $request, Customer $customer, Facility $facility)
    {
        $validated = $request->validated();

        $ext = $request->file('file')->getClientOriginalExtension();

        $filename = "{$validated['document_number']}_{$customer->short_name}_{$facility->sein}.{$ext}";

        $path = $request->file('file')->storeAs(
            'snapp_tax_docs',
            $filename,
            config('filesystems.default')
        );

        $facilityId = $facility->id ?: null;

        CustomerTaxDocument::create([
            'customer_id' => $customer->id,
            'facility_id' => $facilityId,
            'document_number' => $validated['document_number'],
            'file_path' => $path,
        ]);

        return redirect()->back();
    }
}
