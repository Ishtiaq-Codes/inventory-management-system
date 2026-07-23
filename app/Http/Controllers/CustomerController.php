<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use Str;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('user_id', auth()->id())->count();

        return view('customers.index', [
            'customers' => $customers
        ]);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        /**
         * Handle upload an image
         */
        $image = '';
        if ($request->hasFile('photo')) {
            $image = $request->file('photo')->store('customers', 'public');
        }
        $customer = Customer::create([
            'user_id' => auth()->id(),
            'uuid' => Str::uuid(),
            'photo' => $image,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'shopname' => $request->shopname,
            'type' => $request->type,
            'account_holder' => $request->account_holder,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'address' => $request->address,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'customer' => $customer,
                'message' => 'New customer has been created!'
            ]);
        }

        return redirect()
            ->route('customers.index')
            ->with('success', 'New customer has been created!');
    }

    public function show($uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();
        // Load orders ordered by newest first
        $customer->loadMissing(['quotations', 'orders' => function($query) {
            $query->orderBy('order_date', 'desc')->orderBy('created_at', 'desc');
        }]);

        return view('customers.show', [
            'customer' => $customer
        ]);
    }

    public function edit($uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();
        return view('customers.edit', [
            'customer' => $customer
        ]);
    }

    public function update(UpdateCustomerRequest $request, $uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();

        /**
         * Handle upload image with Storage.
         */
        $image = $customer->photo;
        if ($request->hasFile('photo')) {
            if ($customer->photo) {
                unlink(public_path('storage/') . $customer->photo);
            }
            $image = $request->file('photo')->store('customers', 'public');
        }

        $customer->update([
            'photo' => $image,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'shopname' => $request->shopname,
            'type' => $request->type,
            'account_holder' => $request->account_holder,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'address' => $request->address,
        ]);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer has been updated!');
    }

    public function destroy($uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();
        
        try {
            if ($customer->photo && file_exists(public_path('storage/') . $customer->photo)) {
                unlink(public_path('storage/') . $customer->photo);
            }

            $customer->delete();

            return redirect()
                ->back()
                ->with('success', 'Customer has been deleted!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check if it's a foreign key constraint violation
            if ($e->getCode() == "23000") {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot delete this customer because they have existing orders in the system. Please delete their orders first.');
            }
            return redirect()
                ->back()
                ->with('error', 'An error occurred while trying to delete the customer.');
        }
    }

    public function storeOldBalance(\Illuminate\Http\Request $request, $uuid)
    {
        $request->validate([
            'total_amount' => 'required|numeric|min:0.01',
            'paid_amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $customer = Customer::where('uuid', $uuid)->firstOrFail();

        $total = $request->total_amount;
        $paid = $request->paid_amount;
        $due = max(0, $total - $paid);

        \App\Models\Order::create([
            'customer_id' => $customer->id,
            'payment_type' => 'Cash',
            'pay' => $paid,
            'order_date' => $request->date,
            'order_status' => \App\Enums\OrderStatus::COMPLETE,
            'total_products' => 0,
            'sub_total' => $total,
            'vat' => 0,
            'total' => $total,
            'invoice_no' => 'OLD-BAL-' . strtoupper(Str::random(6)),
            'due' => $due,
            'user_id' => auth()->id(),
            'uuid' => Str::uuid(),
            'notes' => $request->notes
        ]);

        return redirect()
            ->route('customers.show', $customer->uuid)
            ->with('success', 'Old notebook record has been added successfully!');
    }

    /**
     * Find or create the special "Walk-in Customer" for quick cash sales.
     * Returns JSON with the customer id & name.
     */
    public function walkin()
    {
        $customer = Customer::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'name'    => 'Walk-in Customer',
            ],
            [
                'uuid'    => Str::uuid(),
                'phone'   => '0000000000',
                'address' => 'Walk-in / Cash Sale',
            ]
        );

        return response()->json([
            'id'   => $customer->id,
            'name' => $customer->name,
        ]);
    }
}
