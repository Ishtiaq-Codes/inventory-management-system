@extends('layouts.tabler')
@section('page-title', 'Daily Report — ' . $reportDate->format('d M Y'))

@push('page-styles')
<style>
    @media print {
        .sth-sidebar, .sth-topbar, .d-print-none { display: none !important; }
        .sth-main { margin-left: 0 !important; }
        .sth-content { padding: 0 !important; }
        .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; }
        .btn { display: none !important; }
    }
    .report-header {
        background: linear-gradient(135deg, #0f1117, #1a1d27);
        color: white;
        border-radius: 14px;
        padding: 24px 28px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .report-stat {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        border: 1px solid #e5e7ef;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .report-stat-label { font-size: 0.85rem; color: #6b7280; font-weight: 600; }
    .report-stat-value { font-size: 1.3rem; font-weight: 800; }
    .net-cash-box {
        border-radius: 14px;
        padding: 22px 24px;
        margin-top: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
@endpush

@section('content')

{{-- Date Picker --}}
<div class="d-flex align-items-center gap-3 mb-4 d-print-none">
    <form method="GET" action="{{ route('report.daily') }}" class="d-flex gap-2 align-items-center">
        <label class="fw-semibold mb-0">Select Date:</label>
        <input type="date" name="date" class="form-control" style="width:190px"
               value="{{ $reportDate->format('Y-m-d') }}">
        <button type="submit" class="btn btn-primary">View Report</button>
    </form>
    <button onclick="window.print()" class="btn btn-outline-secondary ms-auto">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Print / Save PDF
    </button>
</div>

{{-- Report Header --}}
<div class="report-header">
    <div>
        <div style="font-size:0.8rem; color:#f97316; font-weight:700; text-transform:uppercase; letter-spacing:0.06em;">Daily Closing Report</div>
        <div style="font-size:1.6rem; font-weight:800; margin:4px 0;">{{ $reportDate->format('l, d F Y') }}</div>
        <div style="color:#8b92a5; font-size:0.85rem;">Saleem Tyre House — Generated {{ now()->format('h:i A') }}</div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="rgba(249,115,22,0.3)" stroke-width="1.5">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
    </svg>
</div>

<div class="row g-3">

    {{-- Left Column: Summary Cards --}}
    <div class="col-lg-5">

        <h6 class="fw-bold text-muted text-uppercase mb-3" style="letter-spacing:0.06em;">💰 Sales Summary</h6>

        <div class="report-stat">
            <span class="report-stat-label">Total Invoiced (Sales)</span>
            <span class="report-stat-value text-dark">Rs. {{ number_format($totalSales) }}</span>
        </div>
        <div class="report-stat">
            <span class="report-stat-label">Cash Received (Naad)</span>
            <span class="report-stat-value text-success">Rs. {{ number_format($totalCashIn) }}</span>
        </div>
        <div class="report-stat" style="border-left: 4px solid #dc2626;">
            <span class="report-stat-label">Credit Given (Udhaar)</span>
            <span class="report-stat-value text-danger">Rs. {{ number_format($totalCreditGiven) }}</span>
        </div>

        <h6 class="fw-bold text-muted text-uppercase mb-3 mt-4" style="letter-spacing:0.06em;">🛒 Purchase Summary</h6>

        <div class="report-stat">
            <span class="report-stat-label">Total Purchased</span>
            <span class="report-stat-value text-dark">Rs. {{ number_format($totalPurchases) }}</span>
        </div>
        <div class="report-stat">
            <span class="report-stat-label">Paid to Suppliers</span>
            <span class="report-stat-value text-dark">Rs. {{ number_format($totalPaidToSuppliers) }}</span>
        </div>

        <h6 class="fw-bold text-muted text-uppercase mb-3 mt-4" style="letter-spacing:0.06em;">📦 Expenses (Kharch)</h6>

        <div class="report-stat" style="border-left: 4px solid #d97706;">
            <span class="report-stat-label">Total Expenses</span>
            <span class="report-stat-value" style="color:#d97706">Rs. {{ number_format($totalExpenses) }}</span>
        </div>

        {{-- Net Cash --}}
        <div class="net-cash-box mt-3 {{ $netCash >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
            <div>
                <div style="font-size:0.8rem; font-weight:700; opacity:0.8; text-transform:uppercase; letter-spacing:0.06em;">Net Cash in Hand</div>
                <div style="font-size:0.7rem; opacity:0.7; margin-top:2px;">Cash Received − Paid Suppliers − Expenses</div>
            </div>
            <div style="font-size:2rem; font-weight:900;">Rs. {{ number_format($netCash) }}</div>
        </div>

        @if($lowStockProducts->count() > 0)
        <div class="card mt-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <strong>⚠️ Low Stock Alert ({{ $lowStockProducts->count() }} Products)</strong>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($lowStockProducts as $product)
                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                    <span>{{ $product->name }}</span>
                    <span class="badge bg-danger">{{ $product->quantity }} left</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

    </div>

    {{-- Right Column: Detail Tables --}}
    <div class="col-lg-7">

        {{-- Orders --}}
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title mb-0">Sales / Orders ({{ $orders->count() }})</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-sm card-table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Paid</th>
                            <th class="text-end">Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="text-muted small">{{ $order->invoice_no }}</td>
                            <td>{{ $order->customer_name ?? '—' }}</td>
                            <td class="text-end">{{ number_format($order->total) }}</td>
                            <td class="text-end text-success">{{ number_format($order->pay) }}</td>
                            <td class="text-end {{ $order->due > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
                                {{ $order->due > 0 ? number_format($order->due) : '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted">No sales today.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Expenses --}}
        @if($expenses->count() > 0)
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title mb-0">Expenses / Kharch ({{ $expenses->count() }})</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-sm card-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $expense->category }}</span></td>
                            <td class="text-muted small">{{ $expense->description ?? '—' }}</td>
                            <td class="text-end fw-bold text-danger">{{ number_format($expense->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Purchases --}}
        @if($purchases->count() > 0)
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Purchases Today ({{ $purchases->count() }})</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-sm card-table">
                    <thead>
                        <tr>
                            <th>Ref</th>
                            <th>Supplier</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                        <tr>
                            <td class="text-muted small">{{ $purchase->purchase_no }}</td>
                            <td>{{ $purchase->supplier->name ?? '—' }}</td>
                            <td class="text-end">{{ number_format($purchase->total_amount) }}</td>
                            <td class="text-end text-success">{{ number_format($purchase->paid_amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
