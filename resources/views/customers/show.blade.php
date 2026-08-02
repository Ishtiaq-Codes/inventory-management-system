@extends('layouts.tabler')

@pushonce('page-styles')
    <style>
        @media print {
            @page { margin: 0; }
            body { background: #fff !important; color: #000 !important; margin: 1cm; padding-top: 1cm; }
            .navbar, .card-footer, .btn, .modal, .d-print-none, .page-header { display: none !important; }
            .page-body { margin-top: 0 !important; padding-top: 0 !important; }
            .card { border: none !important; box-shadow: none !important; }
            .table-responsive { overflow: visible !important; }
            table th, table td { color: #000 !important; padding: 4px 8px !important; }
            .col-print-12 { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
            .col-print-4 { width: 33.333333% !important; flex: 0 0 33.333333% !important; max-width: 33.333333% !important; }
            .row-print { display: flex !important; flex-wrap: wrap !important; }
            .d-print-block { display: block !important; }
        }
    </style>
@endpushonce

@section('content')
    <div class="page-header">
        <div class="container-xl">
            <div class="row g-2 align-items-center mb-3">
                <div class="col">
                    <h2 class="page-title">
                        {{ $customer->name }} - Khata Statement
                    </h2>
                </div>
            </div>

            <div class="d-print-none">
                @include('partials._breadcrumbs', ['model' => $customer])
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <!-- Print Header -->
            <div class="d-none d-print-block text-center mb-4 mt-4">
                <div style="display: flex; align-items: center; justify-content: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 5px;">
                    <img src="{{ asset('assets/img/logo.jpeg') }}" alt="Logo" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px; object-fit: cover;">
                    <h1 style="font-size: 32px; font-weight: bold; margin: 0;">Saleem Tyre House</h1>
                </div>
                <div style="margin-bottom: 15px; font-size: 16px;">
                    <strong>Haji Naeem Ur Rehman:</strong> 0333-6881325 &nbsp;|&nbsp; <strong>Bilal Naeem:</strong> 0340-1745324
                </div>
                <h3>Customer Khata Statement</h3>
            </div>

            <div class="row row-cards">
                <div class="row">
                    <div class="col-lg-4 d-print-none">
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

                    <div class="col-lg-8 col-print-12">
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
                                        <td><strong>{{ $customer->name }}</strong></td>
                                    </tr>
                                    <tr class="d-print-none">
                                        <td>Email address</td>
                                        <td>{{ $customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone number</td>
                                        <td><strong>{{ $customer->phone }}</strong></td>
                                    </tr>
                                    <tr class="d-print-none">
                                        <td>Old Notebook Page</td>
                                        <td>
                                            @if($customer->page_number)
                                                <span class="badge bg-green-lt">Page {{ $customer->page_number }}</span>
                                            @else
                                                <span class="text-muted">Not recorded</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>{{ $customer->address }}</td>
                                    </tr>
                                    <tr class="d-print-none">
                                        <td>Account holder</td>
                                        <td>{{ $customer->account_holder }}</td>
                                    </tr>
                                    <tr class="d-print-none">
                                        <td>Account number</td>
                                        <td>{{ $customer->account_number }}</td>
                                    </tr>
                                    <tr class="d-print-none">
                                        <td>Bank name</td>
                                        <td>{{ $customer->bank_name }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer text-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#receivePaymentModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="7" y="9" width="14" height="10" rx="2" /><circle cx="14" cy="14" r="2" /><path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" /></svg>
                                    {{ __('Receive Payment (Jama)') }}
                                </button>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addOldDebtModal">
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">{{ __('Ledger / Order History') }}</h3>
                                <button type="button" class="btn btn-secondary d-print-none" onclick="window.print()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                                    {{ __('Print Statement') }}
                                </button>
                            </div>
                            
                            @php
                                $totalPurchases = $customer->orders->sum('total');
                                $totalPaid = $customer->orders->sum('pay');
                                $totalDue = $customer->orders->sum('due');
                            @endphp

                            <div class="card-body">
                                <div class="row mb-3 row-print">
                                    <div class="col-12 col-md-6 col-lg-4 col-print-4 mb-3">
                                        <div class="card bg-primary text-primary-fg">
                                            <div class="card-body">
                                                <div class="h1 mb-3 fw-bolder">{{ Number::currency($totalPurchases, 'PKR') }}</div>
                                                <div class="d-flex mb-2">
                                                    <div class="fw-bold fs-3">Total Purchases</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 col-print-4 mb-3">
                                        <div class="card bg-success text-success-fg">
                                            <div class="card-body">
                                                <div class="h1 mb-3 fw-bolder">{{ Number::currency($totalPaid, 'PKR') }}</div>
                                                <div class="d-flex mb-2">
                                                    <div class="fw-bold fs-3">Total Paid</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 col-print-4 mb-3">
                                        <div class="card bg-danger text-danger-fg">
                                            <div class="card-body">
                                                <div class="h1 mb-3 fw-bolder">{{ Number::currency($totalDue, 'PKR') }}</div>
                                                <div class="d-flex mb-2">
                                                    <div class="fw-bold fs-3">Total Current Debt (Due)</div>
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
                                                <th>Type / Invoice</th>
                                                <th>Description / Notes</th>
                                                <th>Total Bill (+)</th>
                                                <th>Amount Paid (-)</th>
                                                <th>Balance Due</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $runningBalance = 0;
                                                $sortedOrders = $customer->orders->sortBy('created_at');
                                            @endphp
                                            @forelse($sortedOrders as $order)
                                                @php
                                                    $runningBalance += $order->total;
                                                    $runningBalance -= $order->pay;
                                                @endphp
                                                <tr>
                                                    <td>{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') : $order->created_at->format('d-m-Y') }}</td>
                                                    <td>
                                                        @if(str_starts_with($order->invoice_no, 'OLD-BAL'))
                                                            <span class="badge bg-warning text-white">Old Balance</span>
                                                        @elseif(str_starts_with($order->invoice_no, 'PAY-'))
                                                            <span class="badge bg-success text-white">Payment (Jama)</span>
                                                        @else
                                                            <span class="badge bg-primary text-white">Sale / Order</span><br>
                                                            <small class="text-muted">{{ $order->invoice_no }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($order->total_products == 0)
                                                            <span class="text-muted">{{ $order->notes ?? 'Khata Update' }}</span>
                                                        @else
                                                            {{ $order->total_products }} items
                                                            @if($order->notes)
                                                                <br><small class="text-muted">{{ $order->notes }}</small>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="text-danger">{{ $order->total > 0 ? Number::currency($order->total, 'PKR') : '-' }}</td>
                                                    <td class="text-success">{{ $order->pay > 0 ? Number::currency($order->pay, 'PKR') : '-' }}</td>
                                                    <td class="fw-bold text-primary">{{ Number::currency($runningBalance, 'PKR') }}</td>
                                                    <td>
                                                        @if(str_starts_with($order->invoice_no, 'INV-'))
                                                            <a href="{{ route('orders.show', $order->uuid) }}" class="btn btn-sm btn-outline-primary">View Order</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No Khata records found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Print Footer (Formula & Final Balance) -->
                                <div class="d-none d-print-block mt-4">
                                    <div style="border: 1px solid #ccc; padding: 10px; border-radius: 4px; background: #f8f9fa;">
                                        <strong>How to read this Khata:</strong><br>
                                        <em>Previous Balance Due + Total Bill (+) - Amount Paid (-) = New Balance Due</em>
                                    </div>
                                    
                                    <h2 class="text-end mt-4" style="font-size: 24px;">
                                        <strong>Final Amount Due:</strong> 
                                        <span style="border-bottom: 2px solid #000;">{{ Number::currency($totalDue, 'PKR') }}</span>
                                    </h2>
                                    
                                    <p class="text-end text-muted mt-2">
                                        <em>Statement Generated on: {{ now()->format('d-m-Y h:i A') }}</em>
                                    </p>
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

    <!-- Modal for Receive Payment -->
    <div class="modal modal-blur fade" id="receivePaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('customers.addPayment', $customer->uuid) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Receive Payment (Khata Jama)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label required">Amount Received</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="number" step="0.01" class="form-control" name="amount" required placeholder="e.g. 5000">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label required">Date</label>
                                <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Notes / Description (Optional)</label>
                                <input type="text" class="form-control" name="notes" placeholder="e.g. Paid in cash via Ali...">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Payment</button>
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
