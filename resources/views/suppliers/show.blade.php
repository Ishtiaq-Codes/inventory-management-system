@extends('layouts.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">
                    {{ $supplier->name }}
                </h2>
            </div>
        </div>

        @include('partials._breadcrumbs', ['model' => $supplier])
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">
                            {{ __('Profile Image') }}
                        </h3>

                        <img id="image-preview"
                             class="img-account-profile mb-2"
                             src="{{ $supplier->photo ? asset('storage/' . $supplier->photo) : asset('assets/img/demo/user-placeholder.svg') }}"
                             alt=""
                        >
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">
                                {{ __('Supplier Details') }}
                            </h3>
                        </div>

                        <div class="card-actions">
                            <x-action.close route="{{ route('suppliers.index') }}" />
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered card-table table-vcenter text-nowrap datatable">
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td>{{ $supplier->name }}</td>
                                </tr>
                                <tr>
                                    <td>Email address</td>
                                    <td>{{ $supplier->email }}</td>
                                </tr>
                                <tr>
                                    <td>Phone number</td>
                                    <td>{{ $supplier->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>{{ $supplier->address }}</td>
                                </tr>
                                <tr>
                                    <td>Shop name</td>
                                    <td>{{ $supplier->shopname }}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>{{ $supplier->type->label() }}</td>
                                </tr>
                                <tr>
                                    <td>Account holder</td>
                                    <td>{{ $supplier->account_holder }}</td>
                                </tr>
                                <tr>
                                    <td>Account number</td>
                                    <td>{{ $supplier->account_number }}</td>
                                </tr>
                                <tr>
                                    <td>Bank name</td>
                                    <td>{{ $supplier->bank_name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSupplierPaymentModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="7" y="9" width="14" height="10" rx="2" /><circle cx="14" cy="14" r="2" /><path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" /></svg>
                            {{ __('Receive Payment (Jama)') }}
                        </button>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addOldDebtModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-notebook" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h11a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-11a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1m3 0v18" /><path d="M13 8l2 0" /><path d="M13 12l2 0" /></svg>
                            {{ __('Add Old Notebook Debt') }}
                        </button>
                        <a class="btn btn-info" href="{{ route('suppliers.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                            {{ __('Back') }}
                        </a>
                        <x-button.edit class="btn btn-outline-warning" route="{{ route('suppliers.edit', $supplier->uuid) }}">
                            {{ __('Edit') }}
                        </x-button.edit>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Ledger / Purchase History') }}</h3>
                    </div>
                    
                    @php
                        $totalPurchases = $supplier->purchases->sum('total_amount');
                        $totalPaid = $supplier->purchases->sum('paid_amount');
                        $totalDue = $totalPurchases - $totalPaid;
                    @endphp

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card bg-primary text-primary-fg">
                                    <div class="card-body">
                                        <div class="h1 mb-3">{{ Number::currency($totalPurchases, 'PKR') }}</div>
                                        <div class="d-flex mb-2">
                                            <div>Total Bought</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card bg-success text-success-fg">
                                    <div class="card-body">
                                        <div class="h1 mb-3">{{ Number::currency($totalPaid, 'PKR') }}</div>
                                        <div class="d-flex mb-2">
                                            <div>Total Paid to Supplier</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card bg-danger text-danger-fg">
                                    <div class="card-body">
                                        <div class="h1 mb-3">{{ Number::currency($totalDue, 'PKR') }}</div>
                                        <div class="d-flex mb-2">
                                            <div>Total Current Debt (Due)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Purchase No</th>
                                        <th>Status</th>
                                        <th>Products / Notes</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supplier->purchases as $purchase)
                                        @php
                                            $due = $purchase->total_amount - $purchase->paid_amount;
                                        @endphp
                                        <tr>
                                            <td>{{ $purchase->date->format('d-m-Y') }}</td>
                                            <td>{{ $purchase->purchase_no }}</td>
                                            <td>
                                                @if($purchase->status->value == 1 || $purchase->status == 'Approved')
                                                    <span class="badge bg-success text-white">Approved</span>
                                                @else
                                                    <span class="badge bg-warning text-white">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(str_starts_with($purchase->purchase_no, 'OLD-BAL'))
                                                    <span class="text-muted">Notebook Record: {{ $purchase->notes }}</span>
                                                @elseif(str_starts_with($purchase->purchase_no, 'PAY-'))
                                                    <span class="text-success fw-bold">Payment (Jama): {{ $purchase->notes }}</span>
                                                @else
                                                    Standard Purchase
                                                @endif
                                            </td>
                                            <td>
                                                @if(str_starts_with($purchase->purchase_no, 'PAY-'))
                                                    <span class="text-muted">-</span>
                                                @else
                                                    {{ Number::currency($purchase->total_amount, 'PKR') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(str_starts_with($purchase->purchase_no, 'PAY-'))
                                                    <span class="text-success fw-bold">{{ Number::currency($purchase->paid_amount, 'PKR') }}</span>
                                                @else
                                                    {{ Number::currency($purchase->paid_amount, 'PKR') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(str_starts_with($purchase->purchase_no, 'PAY-'))
                                                    <span class="text-success fw-bold">-{{ Number::currency($purchase->paid_amount, 'PKR') }}</span>
                                                @else
                                                    {{ Number::currency($due, 'PKR') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(!str_starts_with($purchase->purchase_no, 'PAY-') && !str_starts_with($purchase->purchase_no, 'OLD-BAL'))
                                                    <a href="{{ route('purchases.show', $purchase->uuid) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for adding old notebook debt -->
<div class="modal modal-blur fade" id="addOldDebtModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('suppliers.storeOldBalance', $supplier->uuid) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Old Notebook Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label required">Total Bill Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="number" step="0.01" class="form-control" name="total_amount" id="supp_total_amount" required placeholder="e.g. 1000">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label required">Amount Paid</label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="number" step="0.01" class="form-control" name="paid_amount" id="supp_paid_amount" required placeholder="e.g. 200">
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Debit Left (Due Amount)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="text" class="form-control bg-light" id="supp_due_amount" readonly placeholder="Calculated automatically...">
                            </div>
                            <small class="form-hint">This is the debt you still owe the supplier for this record.</small>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label required">Date of Record</label>
                            <input type="date" class="form-control" name="date" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label">Products / Description (Optional)</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="e.g. Purchased 100 boxes of items..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Notebook Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for adding payment (Jama) -->
<div class="modal modal-blur fade" id="addSupplierPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('suppliers.addPayment', $supplier->uuid) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cash me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="7" y="9" width="14" height="10" rx="2" /><circle cx="14" cy="14" r="2" /><path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" /></svg>
                        Receive Payment (Jama)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Record a payment you are making to <strong>{{ $supplier->name }}</strong>. This will reduce your total debt balance.
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label required">Payment Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">PKR</span>
                                <input type="number" step="0.01" class="form-control" name="amount" required placeholder="e.g. 5000">
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label required">Date of Payment</label>
                            <input type="date" class="form-control" name="date" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label">Payment Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="2" placeholder="e.g. Paid via Easypaisa or Cash deposit"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('page-scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const totalInput = document.getElementById('supp_total_amount');
        const paidInput = document.getElementById('supp_paid_amount');
        const dueInput = document.getElementById('supp_due_amount');

        function calculateDue() {
            const total = parseFloat(totalInput.value) || 0;
            const paid = parseFloat(paidInput.value) || 0;
            const due = total - paid;
            dueInput.value = due < 0 ? 0 : due.toFixed(2);
        }

        totalInput.addEventListener('input', calculateDue);
        paidInput.addEventListener('input', calculateDue);
    });
</script>
@endpush
@endsection
