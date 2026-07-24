@extends('layouts.tabler')
@section('page-title', 'Expenses (Kharch)')

@push('page-styles')
<style>
    .exp-cat-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .exp-cat-electricity { background: #fff3cd; color: #856404; }
    .exp-cat-salary      { background: #d1e7dd; color: #0a3622; }
    .exp-cat-rent        { background: #cfe2ff; color: #084298; }
    .exp-cat-transport   { background: #f8d7da; color: #58151c; }
    .exp-cat-other       { background: #e2e3e5; color: #41464b; }
</style>
@endpush

@section('content')
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-lg-4 mb-3">
        <div class="card text-center" style="border-left:4px solid #dc2626;">
            <div class="card-body py-3">
                <div class="text-muted small fw-semibold">Today's Expenses</div>
                <div class="fs-3 fw-bold text-danger mt-1">Rs. {{ number_format($totalToday) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 mb-3">
        <div class="card text-center" style="border-left:4px solid #d97706;">
            <div class="card-body py-3">
                <div class="text-muted small fw-semibold">This Month's Expenses</div>
                <div class="fs-3 fw-bold" style="color:#d97706">Rs. {{ number_format($totalThisMonth) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Add Expense Form --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Record New Expense
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label required">Date</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                               value="{{ old('date', today()->format('Y-m-d')) }}" required>
                        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Category</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">— Select Category —</option>
                            <option value="Electricity" {{ old('category') == 'Electricity' ? 'selected' : '' }}>⚡ Electricity (Bijli)</option>
                            <option value="Salary"      {{ old('category') == 'Salary'      ? 'selected' : '' }}>👷 Salary / Wages</option>
                            <option value="Rent"        {{ old('category') == 'Rent'        ? 'selected' : '' }}>🏠 Rent (Kiraya)</option>
                            <option value="Transport"   {{ old('category') == 'Transport'   ? 'selected' : '' }}>🚗 Transport / Delivery</option>
                            <option value="Other"       {{ old('category') == 'Other'       ? 'selected' : '' }}>📦 Other (Other Kharch)</option>
                        </select>
                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Amount (Rs.)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rs.</span>
                            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                                   min="1" step="1" placeholder="e.g. 5000"
                                   value="{{ old('amount') }}" required>
                        </div>
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <input type="text" name="description" class="form-control"
                               placeholder="e.g. WAPDA bill for June"
                               value="{{ old('description') }}">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Save Expense
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Expense List --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">All Expenses</h3>
                <a href="{{ route('report.daily') }}" class="btn btn-sm btn-outline-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Daily Report
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover card-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="text-end">Amount</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>{{ $expense->date->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $cls = match($expense->category) {
                                            'Electricity' => 'exp-cat-electricity',
                                            'Salary'      => 'exp-cat-salary',
                                            'Rent'        => 'exp-cat-rent',
                                            'Transport'   => 'exp-cat-transport',
                                            default       => 'exp-cat-other',
                                        };
                                    @endphp
                                    <span class="exp-cat-badge {{ $cls }}">{{ $expense->category }}</span>
                                </td>
                                <td class="text-muted">{{ $expense->description ?? '—' }}</td>
                                <td class="text-end fw-bold text-danger">Rs. {{ number_format($expense->amount) }}</td>
                                <td class="text-center">
                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this expense record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No expenses recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($expenses->hasPages())
            <div class="card-footer">
                {{ $expenses->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
