<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} — Saleem Tyre House</title>

    <!-- CSS files -->
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/demo.min.css') }}" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --tblr-font-sans-serif: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --sth-orange: #f97316;
            --sth-orange-dark: #ea6a0a;
            --sth-dark: #0f1117;
            --sth-dark-2: #1a1d27;
            --sth-dark-3: #232736;
            --sth-border: rgba(255,255,255,0.08);
            --sth-text-muted: #8b92a5;
        }

        * { box-sizing: border-box; }

        body {
            font-family: var(--tblr-font-sans-serif);
            background: #f0f2f5;
            font-feature-settings: "cv03","cv04","cv11";
        }

        /* ===== DARK SIDEBAR ===== */
        .sth-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            background: var(--sth-dark);
            border-right: 1px solid var(--sth-border);
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sth-sidebar::-webkit-scrollbar { width: 4px; }
        .sth-sidebar::-webkit-scrollbar-track { background: transparent; }
        .sth-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

        .sth-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--sth-border);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .sth-logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 8px;
        }

        .sth-logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--sth-orange), var(--sth-orange-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sth-logo-text {
            line-height: 1.2;
        }

        .sth-logo-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            display: block;
        }

        .sth-logo-subtitle {
            font-size: 0.7rem;
            color: var(--sth-text-muted);
            display: block;
        }

        .sth-nav {
            padding: 12px 0;
            flex: 1;
        }

        .sth-nav-section {
            padding: 16px 16px 6px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--sth-text-muted);
        }

        .sth-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            color: #9ca3b0;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0;
            transition: all 0.15s ease;
            margin: 1px 8px;
            border-radius: 8px;
            cursor: pointer;
        }

        .sth-nav-item:hover {
            background: rgba(255,255,255,0.06);
            color: #fff;
        }

        .sth-nav-item.active {
            background: linear-gradient(135deg, rgba(249,115,22,0.2), rgba(249,115,22,0.1));
            color: var(--sth-orange);
            border-left: 3px solid var(--sth-orange);
            margin-left: 5px;
            padding-left: 13px;
        }

        .sth-nav-item svg {
            flex-shrink: 0;
            opacity: 0.7;
        }

        .sth-nav-item.active svg,
        .sth-nav-item:hover svg {
            opacity: 1;
        }

        .sth-nav-dropdown {
            overflow: hidden;
        }

        .sth-nav-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            color: #9ca3b0;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
            margin: 1px 8px;
            border-radius: 8px;
            cursor: pointer;
            justify-content: space-between;
        }

        .sth-nav-dropdown-toggle:hover,
        .sth-nav-dropdown-toggle.active {
            background: rgba(255,255,255,0.06);
            color: #fff;
        }

        .sth-nav-dropdown-toggle.active {
            background: linear-gradient(135deg, rgba(249,115,22,0.2), rgba(249,115,22,0.1));
            color: var(--sth-orange);
        }

        .sth-nav-dropdown-inner {
            display: none;
        }

        .sth-nav-dropdown.open .sth-nav-dropdown-inner {
            display: block;
        }

        .sth-nav-dropdown-inner .sth-nav-item {
            padding-left: 42px;
            font-size: 0.82rem;
        }

        .sth-dropdown-arrow {
            transition: transform 0.2s ease;
            margin-left: auto;
        }

        .sth-nav-dropdown.open .sth-dropdown-arrow {
            transform: rotate(180deg);
        }

        .sth-nav-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sth-sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--sth-border);
        }

        .sth-user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
            cursor: pointer;
            transition: background 0.15s;
            text-decoration: none;
        }

        .sth-user-card:hover {
            background: rgba(255,255,255,0.08);
        }

        .sth-user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            object-fit: cover;
        }

        .sth-user-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: #fff;
            line-height: 1.2;
        }

        .sth-user-role {
            font-size: 0.7rem;
            color: var(--sth-text-muted);
        }

        /* ===== MAIN CONTENT ===== */
        .sth-main {
            margin-left: 240px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== TOP BAR ===== */
        .sth-topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7ef;
            padding: 0 24px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .sth-topbar-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1a1d27;
        }

        .sth-topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sth-topbar-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6f9;
            color: #6b7280;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }

        .sth-topbar-btn:hover {
            background: #e8ebf0;
            color: #374151;
        }

        /* ===== PAGE CONTENT ===== */
        .sth-content {
            flex: 1;
            padding: 24px;
        }

        /* ===== ALERTS / FLASH ===== */
        .sth-alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .sth-alert-success {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .sth-alert-error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        /* ===== BADGE for nav ===== */
        .sth-badge {
            background: var(--sth-orange);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 99px;
            margin-left: auto;
        }

        /* ===== FOOTER ===== */
        .sth-footer {
            padding: 16px 24px;
            color: var(--sth-text-muted);
            font-size: 0.75rem;
            border-top: 1px solid #e5e7ef;
            display: flex;
            justify-content: space-between;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sth-sidebar { transform: translateX(-240px); }
            .sth-sidebar.open { transform: translateX(0); }
            .sth-main { margin-left: 0; }
        }

        /* ===== OVERRIDE TABLER CARDS ===== */
        .card {
            border-radius: 12px !important;
            border: 1px solid #e5e7ef !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(249,115,22,0.15) !important;
            border-color: var(--sth-orange) !important;
        }

        .btn-primary {
            background: var(--sth-orange) !important;
            border-color: var(--sth-orange-dark) !important;
        }

        .btn-primary:hover {
            background: var(--sth-orange-dark) !important;
        }

        /* Page header styling */
        .page-header {
            background: transparent;
            padding: 0 0 20px 0;
            border-bottom: none;
        }

        .page-title { font-size: 1.4rem; font-weight: 700; color: #1a1d27; }
        .page-pretitle { font-size: 0.75rem; color: var(--sth-text-muted); text-transform: uppercase; letter-spacing: 0.06em; font-weight: 600; }

        /* Logout form */
        .sth-logout-form { margin: 0; }
    </style>

    {{-- Page Styles --}}
    @stack('page-styles')
    @livewireStyles
    <link href="{{ asset('vendor/rappasoft/livewire-tables/css/laravel-livewire-tables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/rappasoft/livewire-tables/css/laravel-livewire-tables-thirdparty.min.css') }}" rel="stylesheet">
</head>

<body>
    <script src="{{ asset('dist/js/demo-theme.min.js') }}"></script>

    <!-- Sidebar -->
    <aside class="sth-sidebar" id="sth-sidebar">

        <a href="{{ route('dashboard') }}" class="sth-logo" style="display: flex; justify-content: center; padding: 25px 0 15px 0;">
            <img src="{{ asset('assets/img/logo.jpeg') }}" alt="Saleem Tyre House" style="width: 140px; height: auto; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
        </a>

        <!-- Navigation -->
        <nav class="sth-nav">

            <div class="sth-nav-section">Main</div>

            <a href="{{ route('dashboard') }}"
               class="sth-nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Dashboard
            </a>

            <a href="{{ route('orders.create') }}"
               class="sth-nav-item {{ request()->is('orders/create') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Sale / Order
                <span class="sth-badge" style="margin-left:auto;">+</span>
            </a>

            <div class="sth-nav-section">Inventory</div>

            <a href="{{ route('products.index') }}"
               class="sth-nav-item {{ request()->is('products*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><path d="M8 21h8m-4-4v4"/></svg>
                Products
            </a>

            <div class="sth-nav-dropdown {{ request()->is('orders*') ? 'open' : '' }}">
                <div class="sth-nav-dropdown-toggle {{ request()->is('orders*') ? 'active' : '' }}" onclick="toggleDropdown(this)">
                    <div class="sth-nav-left">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        Orders
                    </div>
                    <svg class="sth-dropdown-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="sth-nav-dropdown-inner">
                    <a href="{{ route('orders.index') }}" class="sth-nav-item {{ request()->is('orders') ? 'active' : '' }}">All Orders</a>
                    <a href="{{ route('orders.complete') }}" class="sth-nav-item {{ request()->is('orders/complete') ? 'active' : '' }}">Completed</a>
                    <a href="{{ route('due.index') }}" class="sth-nav-item {{ request()->is('due*') ? 'active' : '' }}">
                        Due / Credit
                        @php $dueCnt = \App\Models\Order::where('due','>',0)->count(); @endphp
                        @if($dueCnt > 0)<span class="sth-badge">{{ $dueCnt }}</span>@endif
                    </a>
                </div>
            </div>

            <div class="sth-nav-dropdown {{ request()->is('purchases*') ? 'open' : '' }}">
                <div class="sth-nav-dropdown-toggle {{ request()->is('purchases*') ? 'active' : '' }}" onclick="toggleDropdown(this)">
                    <div class="sth-nav-left">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        Purchases
                    </div>
                    <svg class="sth-dropdown-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="sth-nav-dropdown-inner">
                    <a href="{{ route('purchases.index') }}" class="sth-nav-item">All Purchases</a>
                    <a href="{{ route('purchases.create') }}" class="sth-nav-item">New Purchase</a>
                    <a href="{{ route('purchases.approvedPurchases') }}" class="sth-nav-item">Approved</a>
                </div>
            </div>

            <a href="{{ route('quotations.index') }}"
               class="sth-nav-item {{ request()->is('quotations*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Quotations
            </a>

            <a href="{{ route('expenses.index') }}"
               class="sth-nav-item {{ request()->is('expenses*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Expenses (Kharch)
            </a>

            <a href="{{ route('report.daily') }}"
               class="sth-nav-item {{ request()->is('report/daily*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Daily Report
            </a>


            <div class="sth-nav-section">People</div>

            <a href="{{ route('customers.index') }}"
               class="sth-nav-item {{ request()->is('customers*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Customers
            </a>

            <a href="{{ route('suppliers.index') }}"
               class="sth-nav-item {{ request()->is('suppliers*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                Suppliers
            </a>

            <div class="sth-nav-section">Settings</div>

            <a href="{{ route('categories.index') }}"
               class="sth-nav-item {{ request()->is('categories*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Categories
            </a>

            <a href="{{ route('units.index') }}"
               class="sth-nav-item {{ request()->is('units*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Units
            </a>

        </nav>

        <!-- User footer -->
        <div class="sth-sidebar-footer">
            <div class="dropdown">
                <div class="sth-user-card dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->photo ? asset('storage/profile/' . Auth::user()->photo) : asset('assets/img/illustrations/profiles/admin.jpg') }}"
                         class="sth-user-avatar" alt="User">
                    <div>
                        <div class="sth-user-name">{{ Auth::user()->name }}</div>
                        <div class="sth-user-role">Store Manager</div>
                    </div>
                </div>
                <div class="dropdown-menu dropdown-menu-end mb-2">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        Account Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="post" class="sth-logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="sth-main">

        <!-- Top Bar -->
        <div class="sth-topbar d-print-none">
            <div class="d-flex align-items-center gap-3">
                <button class="sth-topbar-btn d-md-none" onclick="document.getElementById('sth-sidebar').classList.toggle('open')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div>
                    <div class="sth-topbar-title">
                        @yield('page-title', 'Dashboard')
                    </div>
                </div>
            </div>
            <div class="sth-topbar-right">
                <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary d-none d-sm-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    New Order
                </a>
                <div class="nav-item dropdown">
                    <a href="#" class="sth-topbar-btn" data-bs-toggle="dropdown" aria-label="Notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <span class="dropdown-header">Notifications</span>
                        <a class="dropdown-item text-muted" href="#">No new notifications</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <div class="sth-content d-print-none" style="padding-bottom:0; padding-top: 16px;">
            @if(session('success'))
                <div class="sth-alert sth-alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="sth-alert sth-alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <div class="sth-content">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="sth-footer d-print-none flex-wrap gap-2">
            <span>© {{ now()->year }} Saleem Tyre House — All rights reserved.</span>
            <span class="d-flex align-items-center gap-2">
                <a href="https://github.com/sponsors/awaisejaz" target="_blank" class="text-decoration-none text-muted d-flex align-items-center gap-1" rel="noopener">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#ef4444" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    Sponsor
                </a>
                <span>Built by <strong style="font-weight: 900; color: var(--sth-orange); font-size: 0.9rem; letter-spacing: 0.02em;">Awais Ejaz</strong></span>
                <span>·</span>
                <a href="https://github.com/awaisejaz/inventory-management" target="_blank" class="text-decoration-none text-muted d-flex align-items-center gap-1" rel="noopener">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.24c3-.34 6-1.54 6-6.16a5.5 5.5 0 0 0-1.64-3.92A5.4 5.4 0 0 0 18.2 4s-1.34-.43-4.38 1.64a15.7 15.7 0 0 0-8 0C2.78 3.57 1.44 4 1.44 4a5.4 5.4 0 0 0-.22 4.76A5.5 5.5 0 0 0 0 12.6c0 4.62 3 5.82 6 6.16A4.8 4.8 0 0 0 5 22v4"/><path d="M9 18c-5 1.5-5-2.5-7-3"/></svg>
                    GitHub
                </a>
                <span>· v1.0.0</span>
            </span>
        </footer>
    </div>

    <!-- Libs JS -->
    @stack('page-libraries')
    <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
    <script src="{{ asset('dist/js/demo.min.js') }}" defer></script>
    @stack('page-scripts')

    <script src="{{ asset('vendor/livewire/livewire.js') }}" data-update-uri="{{ url('/livewire/update') }}" data-csrf="{{ csrf_token() }}"></script>
    <script src="{{ asset('vendor/rappasoft/livewire-tables/js/laravel-livewire-tables.min.js') }}"></script>
    <script src="{{ asset('vendor/rappasoft/livewire-tables/js/laravel-livewire-tables-thirdparty.min.js') }}"></script>

    <script>
        function toggleDropdown(el) {
            el.parentElement.classList.toggle('open');
        }
    </script>
</body>
</html>
