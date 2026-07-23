@extends('layouts.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center mb-3">
                <div class="col">
                    <h2 class="page-title">
                        {{ $customer->name }}
                    </h2>
                </div>
            </div>

            @include('partials._breadcrumbs', ['model' => $customer])
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    {{ __('Profile Image') }}
                                </h3>

                                <img id="image-preview"
                                     class="img-account-profile mb-2"
                                     src="{{ $customer->photo ? asset('storage/' . $customer->photo) : asset('assets/img/demo/user-placeholder.svg') }}"
                                     alt=""
                                >
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{ __('Customer Details') }}
                                </h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered card-table table-vcenter text-nowrap datatable">
                                    <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td>{{ $customer->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email address</td>
                                        <td>{{ $customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone number</td>
                                        <td>{{ $customer->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>{{ $customer->address }}</td>
                                    </tr>
                                    <tr>
                                        <td>Account holder</td>
                                        <td>{{ $customer->account_holder }}</td>
                                    </tr>
                                    <tr>
                                        <td>Account number</td>
                                        <td>{{ $customer->account_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bank name</td>
                                        <td>{{ $customer->bank_name }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer text-end">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#addOldDebtModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-notebook" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h11a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-11a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1m3 0v18" /><path d="M13 8l2 0" /><path d="M13 12l2 0" /></svg>
                                    {{ __('Add Old Notebook Debt') }}
                                </button>
                                <a class="btn btn-info" href="{{ route('customers.index') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                                    {{ __('Back') }}
                                </a>

                                <a class="btn btn-warning" href="{{ route('customers.edit', $customer->uuid) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                    {{ __('Edit') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Ledger / Order History') }}</h3>
                            </div>
                            
                            @php
                                $totalPurchases = $customer->orders->sum('total');
                                $totalPaid = $customer->orders->sum('pay');
                                $totalDue = $customer->orders->sum('due');
                            @endphp

                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="card bg-primary text-primary-fg">
                                            <div class="card-body">
                                                <div class="h1 mb-3">{{ Number::currency($totalPurchases, 'PKR') }}</div>
                                                <div class="d-flex mb-2">
                                                    <div>Total Purchases</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-success text-success-fg">
                                            <div class="card-body">
                                                <div class="h1 mb-3">{{ Number::currency($totalPaid, 'PKR') }}</div>
                                                <div class="d-flex mb-2">
                                                    <div>Total Paid</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
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
                                                <th>Invoice No</th>
                                                <th>Status</th>
                                                <th>Products / Notes</th>
                                                <th>Total</th>
                                                <th>Paid</th>
                                                <th>Due</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($customer->orders as $order)
                                                <tr>
                                                    <td>{{ $order->order_date->format('d-m-Y') }}</td>
                                                    <td>{{ $order->invoice_no }}</td>
                                                    <td>
                                                        @if($order->order_status->value == 1 || $order->order_status == 'Complete')
                                                            <span class="badge bg-success text-white">Complete</span>
                                                        @elseif($order->order_status->value == 2 || $order->order_status == 'Cancel')
                                                            <span class="badge bg-danger text-white">Cancel</span>
                                                        @else
                                                            <span class="badge bg-warning text-white">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($order->total_products == 0)
                                                            <span class="text-muted">Notebook Record: {{ $order->notes }}</span>
                                                        @else
                                                            {{ $order->total_products }} items
                                                            @if(str_starts_with($order->notes ?? '', 'Walk-in: '))
                                                                <br><small class="text-muted">👤 {{ str_replace('Walk-in: ', '', $order->notes) }}</small>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>{{ Number::currency($order->total, 'PKR') }}</td>
                                                    <td>{{ Number::currency($order->pay, 'PKR') }}</td>
                                                    <td>{{ Number::currency($order->due, 'PKR') }}</td>
                                                    <td>
                                                        <a href="{{ route('orders.show', $order->uuid) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                        @if($order->due > 0)
                                                        <a href="{{ route('due.edit', $order->id) }}" class="btn btn-sm btn-outline-success">Pay Debt</a>
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
    </div>

    <!-- Modal for adding old notebook debt -->
    <div class="modal modal-blur fade" id="addOldDebtModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('customers.storeOldBalance', $customer->uuid) }}" method="POST">
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
                                    <input type="number" step="0.01" class="form-control" name="total_amount" id="total_amount" required placeholder="e.g. 1000">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label required">Amount Paid</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="number" step="0.01" class="form-control" name="paid_amount" id="paid_amount" required placeholder="e.g. 200">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Debit Left (Due Amount)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="text" class="form-control bg-light" id="due_amount" readonly placeholder="Calculated automatically...">
                                </div>
                                <small class="form-hint">This is the debt the customer still owes you for this record.</small>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label required">Date of Record</label>
                                <input type="date" class="form-control" name="date" required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">Products / Description (Optional)</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="e.g. 5 bags of cement, 2 pipes..."></textarea>
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

    @push('page-scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const totalInput = document.getElementById('total_amount');
            const paidInput = document.getElementById('paid_amount');
            const dueInput = document.getElementById('due_amount');

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
