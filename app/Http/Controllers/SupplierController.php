<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use Str;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::where("user_id", auth()->id())->count();

        return view('suppliers.index', [
            'suppliers' => $suppliers
        ]);
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $image = "";
        if ($request->hasFile('photo')) {
            $image = $request->file('photo')->store("supliers", "public");
        }

        $supplier = Supplier::create([
            "user_id" => auth()->id(),
            "uuid" => Str::uuid(),
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
                'supplier' => $supplier,
                'message' => 'New supplier has been created!'
            ]);
        }

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'New supplier has been created!');
    }

    public function show($uuid)
    {
        $supplier = Supplier::where("uuid", $uuid)->firstOrFail();
        $supplier->loadMissing(['purchases' => function($query) {
            $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        }]);

        return view('suppliers.show', [
            'supplier' => $supplier
        ]);
    }

    public function edit($uuid)
    {
        $supplier = Supplier::where("uuid", $uuid)->firstOrFail();
        return view('suppliers.edit', [
            'supplier' => $supplier
        ]);
    }

    public function update(UpdateSupplierRequest $request, $uuid)
    {
        $supplier = Supplier::where("uuid", $uuid)->firstOrFail();

        /**
         * Handle upload image with Storage.
         */
        $image = $supplier->photo;
        if ($request->hasFile('photo')) {

            // Delete Old Photo
            if ($supplier->photo) {
                unlink(public_path('storage/') . $supplier->photo);
            }

            $image = $request->file('photo')->store("supliers", "public");
        }

        $supplier->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'photo' => $image,
            'shopname' => $request->shopname,
            'type' => $request->type,
            'account_holder' => $request->account_holder,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'address' => $request->address,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier has been updated!');
    }

    public function destroy($uuid)
    {
        $supplier = Supplier::where("uuid", $uuid)->firstOrFail();
        /**
         * Delete photo if exists.
         */
        if ($supplier->photo) {
            unlink(public_path('storage/suppliers/') . $supplier->photo);
        }

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier has been deleted!');
    }

    public function storeOldBalance(\Illuminate\Http\Request $request, $uuid)
    {
        $request->validate([
            'total_amount' => 'required|numeric|min:0.01',
            'paid_amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $supplier = Supplier::where('uuid', $uuid)->firstOrFail();

        $total = $request->total_amount;
        $paid = $request->paid_amount;

        \App\Models\Purchase::create([
            'supplier_id' => $supplier->id,
            'date' => $request->date,
            'purchase_no' => 'OLD-BAL-' . strtoupper(Str::random(6)),
            'status' => \App\Enums\PurchaseStatus::PENDING,
            'total_amount' => $total,
            'paid_amount' => $paid,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'user_id' => auth()->id(),
            'uuid' => Str::uuid(),
            'notes' => $request->notes
        ]);

        return redirect()
            ->route('suppliers.show', $supplier->uuid)
            ->with('success', 'Old notebook record has been added successfully!');
    }

    /**
     * Add a generic payment (Jama) to the Supplier Khata.
     */
    public function addPayment(\Illuminate\Http\Request $request, $uuid)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $supplier = Supplier::where('uuid', $uuid)->firstOrFail();

        \App\Models\Purchase::create([
            'supplier_id' => $supplier->id,
            'date' => $request->date,
            'purchase_no' => 'PAY-' . strtoupper(Str::random(6)),
            'status' => \App\Enums\PurchaseStatus::APPROVED, // Approved automatically
            'total_amount' => 0, // No new bill added
            'paid_amount' => $request->amount, // Payment received
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'user_id' => auth()->id(),
            'uuid' => Str::uuid(),
            'notes' => $request->notes ?: 'Payment Sent (Khata Jama)'
        ]);

        return redirect()
            ->route('suppliers.show', $supplier->uuid)
            ->with('success', 'Payment recorded successfully!');
    }
}
