@extends('layouts.tabler')
@section('page-title', 'Dashboard')

@section('content')

@push('page-styles')
<style>
    /* ===== DASHBOARD STYLES ===== */
    .dash-greeting {
        background: linear-gradient(135deg, #0f1117 0%, #1a1d27 50%, #232736 100%);
        border-radius: 16px;
        padding: 28px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(249,115,22,0.15);
    }

    .dash-greeting::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        background: radial-gradient(circle, rgba(249,115,22,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .dash-greeting::after {
        content: '';
        position: absolute;
        bottom: -40px; left: 40%;
        width: 160px; height: 160px;
        background: radial-gradient(circle, rgba(249,115,22,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .dash-greeting-content { position: relative; z-index: 1; }

    .dash-greeting-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 4px 0;
    }

    .dash-greeting-sub {
        font-size: 0.875rem;
        color: #8b92a5;
        margin: 0;
    }

    .dash-greeting-time {
        font-size: 0.8rem;
        color: #f97316;
        font-weight: 600;
        margin-top: 8px;
    }

    .dash-greeting-icon {
        width: 80px;
        height: 80px;
        opacity: 0.15;
        position: relative;
        z-index: 1;
    }

    /* ===== STAT CARDS ===== */
    .dash-stat {
        background: #fff;
        border-radius: 14px;
        padding: 20px;
        border: 1px solid #e5e7ef;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
        text-decoration: none;
        display: block;
    }

    .dash-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        border-color: #d1d5db;
    }

    .dash-stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .dash-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dash-stat-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .dash-stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #111827;
        line-height: 1;
        margin: 4px 0 6px;
    }

    .dash-stat-sub {
        font-size: 0.78rem;
        color: #9ca3af;
    }

    /* ===== HERO METRIC ===== */
    .dash-hero {
        background: linear-gradient(135deg, #0f1117, #1a1d27);
        border-radius: 16px;
        padding: 28px 32px;
        border: 1px solid rgba(249,115,22,0.2);
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .dash-hero::before {
        content: '';
        position: absolute;
        top: -80px; right: -40px;
        width: 280px; height: 280px;
        background: radial-gradient(circle, rgba(249,115,22,0.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .dash-hero-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #f97316;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 8px;
    }

    .dash-hero-title {
        font-size: 0.95rem;
        color: #9ca3af;
        margin-bottom: 6px;
    }

    .dash-hero-value {
        font-size: 2.8rem;
        font-weight: 900;
        color: #fff;
        margin: 0;
        line-height: 1;
    }

    .dash-hero-formula {
        font-size: 0.75rem;
        color: #4b5563;
        margin-top: 8px;
    }

    /* ===== SECTION HEADERS ===== */
    .dash-section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
        margin-top: 6px;
    }

    .dash-section-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a1d27;
    }

    .dash-section-line {
        flex: 1;
        height: 1px;
        background: #e5e7ef;
    }

    /* ===== TODAY CARDS ===== */
    .dash-today {
        background: #fff;
        border-radius: 14px;
        padding: 18px 20px;
        border: 1px solid #e5e7ef;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.2s ease;
    }

    .dash-today:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .dash-today-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .dash-today-label {
        font-size: 0.82rem;
        color: #6b7280;
        margin-bottom: 2px;
    }

    .dash-today-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: #111827;
    }

    /* ===== QUICK ACTIONS ===== */
    .dash-action {
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        border: 1px solid #e5e7ef;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: all 0.2s ease;
    }

    .dash-action:hover {
        border-color: #f97316;
        box-shadow: 0 4px 14px rgba(249,115,22,0.15);
        transform: translateY(-2px);
    }

    .dash-action-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #fff7ed;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
    }

    .dash-action-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
    }
</style>
@endpush

{{-- Greeting Banner --}}
<div class="dash-greeting">
    <div class="dash-greeting-content">
        <h1 class="dash-greeting-title">Welcome back, {{ Auth::user()->name }}! 👋</h1>
        <p class="dash-greeting-sub">Saleem Tyre House — Inventory & Finance Control Panel</p>
        <p class="dash-greeting-time" id="live-time">{{ now()->format('l, d F Y — h:i A') }}</p>
    </div>
    <svg class="dash-greeting-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
        <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/>
        <path d="M12 2v3m0 14v3M2 12h3m14 0h3M4.93 4.93l2.12 2.12m9.9 9.9 2.12 2.12M4.93 19.07l2.12-2.12m9.9-9.9 2.12-2.12"/>
    </svg>
</div>

{{-- Total Business Value Hero --}}
<div class="dash-hero">
    <div class="dash-hero-label">📊 Total Business Value</div>
    <div class="dash-hero-title">Your overall financial standing right now</div>
    <p class="dash-hero-value">Rs. {{ number_format($totalBusinessValue) }}</p>
    <div class="dash-hero-formula">= Stock Value ({{ number_format($totalStockValue) }}) + Sales ({{ number_format($totalSales) }}) − Purchases ({{ number_format($totalPurchases) }})</div>
</div>

{{-- Quick Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="dash-stat">
            <div class="dash-stat-header">
                <div class="dash-stat-label">Products</div>
                <div class="dash-stat-icon" style="background:#fff7ed;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg>
                </div>
            </div>
            <div class="dash-stat-value">{{ $products }}</div>
            <div class="dash-stat-sub">{{ $categories }} categories</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('orders.index') }}" class="dash-stat">
            <div class="dash-stat-header">
                <div class="dash-stat-label">Orders</div>
                <div class="dash-stat-icon" style="background:#f0fdf4;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
            </div>
            <div class="dash-stat-value">{{ $orders }}</div>
            <div class="dash-stat-sub">{{ $todayOrders }} today</div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('purchases.index') }}" class="dash-stat">
            <div class="dash-stat-header">
                <div class="dash-stat-label">Purchases</div>
                <div class="dash-stat-icon" style="background:#eff6ff;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </div>
            </div>
            <div class="dash-stat-value">{{ $purchases }}</div>
            <div class="dash-stat-sub">{{ $todayPurchases }} today</div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('quotations.index') }}" class="dash-stat">
            <div class="dash-stat-header">
                <div class="dash-stat-label">Quotations</div>
                <div class="dash-stat-icon" style="background:#fdf4ff;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9333ea" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
            </div>
            <div class="dash-stat-value">{{ $quotations }}</div>
            <div class="dash-stat-sub">{{ $todayQuotations }} today</div>
        </a>
    </div>
</div>

{{-- Financial Overview --}}
<div class="dash-section-header">
    <span class="dash-section-title">📈 Financial Overview (All-Time)</span>
    <div class="dash-section-line"></div>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="dash-stat" style="border-left: 4px solid #16a34a;">
            <div class="dash-stat-label">Total Sales Revenue</div>
            <div class="dash-stat-value" style="color:#16a34a; font-size:1.5rem;">Rs. {{ number_format($totalSales) }}</div>
            <div class="dash-stat-sub">All invoiced sales ever</div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('due.index') }}" class="dash-stat" style="border-left: 4px solid #2563eb;">
            <div class="dash-stat-label">Total Receivables</div>
            <div class="dash-stat-value" style="color:#2563eb; font-size:1.5rem;">Rs. {{ number_format($totalReceivables) }}</div>
            <div class="dash-stat-sub">Customers still owe you this</div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="dash-stat" style="border-left: 4px solid #dc2626;">
            <div class="dash-stat-label">Total Payables</div>
            <div class="dash-stat-value" style="color:#dc2626; font-size:1.5rem;">Rs. {{ number_format($totalPayables) }}</div>
            <div class="dash-stat-sub">You still owe vendors this</div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('products.index') }}" class="dash-stat" style="border-left: 4px solid #d97706;">
            <div class="dash-stat-label">Stock Value</div>
            <div class="dash-stat-value" style="color:#d97706; font-size:1.5rem;">Rs. {{ number_format($totalStockValue) }}</div>
            <div class="dash-stat-sub">Current inventory invested</div>
        </a>
    </div>
</div>

{{-- Today's Activity --}}
<div class="dash-section-header">
    <span class="dash-section-title">⚡ Today's Activity — {{ now()->format('d M Y') }}</span>
    <div class="dash-section-line"></div>
</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="dash-today">
            <div class="dash-today-dot" style="background:#16a34a;"></div>
            <div>
                <div class="dash-today-label">Sales Today</div>
                <div class="dash-today-value">Rs. {{ number_format($todaySalesAmount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="dash-today">
            <div class="dash-today-dot" style="background:#2563eb;"></div>
            <div>
                <div class="dash-today-label">Cash Received</div>
                <div class="dash-today-value">Rs. {{ number_format($todayReceivedAmount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('due.index') }}" style="text-decoration:none;">
            <div class="dash-today">
                <div class="dash-today-dot" style="background:#dc2626;"></div>
                <div>
                    <div class="dash-today-label">Credit Given</div>
                    <div class="dash-today-value" style="color:{{ $todayCreditAmount > 0 ? '#dc2626' : '#111827' }}">
                        Rs. {{ number_format($todayCreditAmount) }}
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <div class="dash-today">
            <div class="dash-today-dot" style="background:#d97706;"></div>
            <div>
                <div class="dash-today-label">Purchased Today</div>
                <div class="dash-today-value">Rs. {{ number_format($todayPurchasesAmount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('expenses.index') }}" style="text-decoration:none;">
            <div class="dash-today">
                <div class="dash-today-dot" style="background:#7c3aed;"></div>
                <div>
                    <div class="dash-today-label">Expenses (Kharch)</div>
                    <div class="dash-today-value" style="color:{{ $todayExpensesAmount > 0 ? '#7c3aed' : '#111827' }}">
                        Rs. {{ number_format($todayExpensesAmount) }}
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Low Stock Alert --}}
@if($lowStockCount > 0)
<a href="{{ route('products.index') }}" style="text-decoration:none;">
    <div class="alert" style="background:#fef3c7; border:1.5px solid #f59e0b; border-radius:12px; padding:12px 18px; display:flex; align-items:center; gap:12px; margin-bottom:4px;">
        <span style="font-size:1.3rem;">⚠️</span>
        <div>
            <strong style="color:#92400e;">Low Stock Alert:</strong>
            <span style="color:#78350f;"> {{ $lowStockCount }} product{{ $lowStockCount > 1 ? 's are' : ' is' }} running low on stock. Click to view products.</span>
        </div>
    </div>
</a>
@endif

{{-- Quick Actions --}}
<div class="dash-section-header">
    <span class="dash-section-title">🚀 Quick Actions</span>
    <div class="dash-section-line"></div>
</div>
<div class="row g-3">
    <div class="col-4 col-md-2">
        <a href="{{ route('orders.create') }}" class="dash-action">
            <div class="dash-action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
            <div class="dash-action-label">New Order</div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="{{ route('purchases.create') }}" class="dash-action">
            <div class="dash-action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <div class="dash-action-label">New Purchase</div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="{{ route('products.create') }}" class="dash-action">
            <div class="dash-action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div class="dash-action-label">Add Product</div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="{{ route('customers.create') }}" class="dash-action">
            <div class="dash-action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div class="dash-action-label">Add Customer</div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="{{ route('due.index') }}" class="dash-action">
            <div class="dash-action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </div>
            <div class="dash-action-label">View Dues</div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="{{ route('expenses.index') }}" class="dash-action">
            <div class="dash-action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="dash-action-label">Add Kharch</div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="{{ route('report.daily') }}" class="dash-action">
            <div class="dash-action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="dash-action-label">Daily Report</div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="{{ route('quotations.create') }}" class="dash-action">
            <div class="dash-action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div class="dash-action-label">Quotation</div>
        </a>
    </div>
</div>

@endsection

@push('page-scripts')
<script>
    // Live clock
    function updateTime() {
        const el = document.getElementById('live-time');
        if (el) {
            const now = new Date();
            const opts = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
            const time = now.toLocaleTimeString('en-PK', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
            const date = now.toLocaleDateString('en-PK', opts);
            el.textContent = `${date} — ${time}`;
        }
    }
    setInterval(updateTime, 1000);
    updateTime();
</script>
@endpush
