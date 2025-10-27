<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCustomerRequest $request)
    {
        $validatedRequest = $request->validated();

        Customer::create($validatedRequest);

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
    public function update(UpdateCustomerRequest $request, string $id)
    {
        $validatedRequest = $request->validated();

        $customer = Customer::findOrFail($id);

        $data = [
            'account_name' => $validatedRequest['edit_account_name'],
            'short_name' => $validatedRequest['edit_short_name'],
            'customer_number' => $validatedRequest['edit_customer_number'],
        ];

        $customer->update($data);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
  public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

     return redirect()->route('customers.index');
    }
}
