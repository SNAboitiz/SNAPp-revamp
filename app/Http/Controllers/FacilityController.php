<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFacilityRequest;
use App\Http\Requests\UpdateFacilityRequest;
use App\Models\Customer;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facilities = Facility::paginate(10);

        $customers = Customer::orderBy('account_name')->get();

        return view('admin.facilities.index', compact('facilities', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacilityRequest $request)
    {
        $validatedRequest = $request->validated();

        Facility::create($validatedRequest);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacilityRequest $request, string $id)
    {
        $validatedRequest = $request->validated();

        $facility = Facility::findorFail($id);

        $data = [
            'name' => $validatedRequest['edit_name'],
            'sein' => $validatedRequest['edit_sein'],
            'customer_id' => $validatedRequest['customer_id'],
        ];

        $facility->update($data);

        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $facility = Facility::findOrFail($id);
        $facility->delete();

        return redirect()->route('facilities.index');
    }
}
