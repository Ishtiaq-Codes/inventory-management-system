@extends('layouts.tabler')

@pushonce('page-styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    <style>
        .ts-control { border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important; }
        .input-group > .ts-wrapper { flex: 1 1 auto; width: 1%; }
        /* Fix dropdown overlapping and visibility */
        .ts-dropdown {
            z-index: 9999 !important;
            background: #fff;
            border: 1px solid #dce1e7;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        #walkin-btn { position: relative; z-index: 1; }
    </style>
@endpushonce

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">
                                {{ __('New Order') }}
                            </h3>
                        </div>
                        <div class="card-actions btn-actions">
                            <x-action.close route="{{ route('orders.index') }}"/>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('invoice.create') }}" method="POST" id="invoiceForm">
                        @csrf
                            <div class="row gx-3 mb-3">
                                @include('partials.session')
                                <div class="col-12 col-md-6 col-xl-4 mb-3">
                                    <label for="purchase_date" class="small my-1">
                                        {{ __('Date') }}
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input name="purchase_date" id="purchase_date" type="date"
                                           class="form-control example-date-input @error('purchase_date') is-invalid @enderror"
                                           value="{{ old('purchase_date') ?? now()->format('Y-m-d') }}"
                                           required
                                    >

                                    @error('purchase_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-xl-4 mb-3">
                                    <label class="small mb-1" for="customer_id">
                                        {{ __('Customer') }}
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <select class="form-select form-control-solid @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                            <option value="" selected="" disabled="">
                                                Select a customer:
                                            </option>

                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}" @selected( old('customer_id') == $customer->id)>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCustomerModal" title="Add New Customer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                                        </button>
                                    </div>

                                    {{-- Walk-in / Cash Customer quick button --}}
                                    <button type="button" id="walkin-btn"
                                            class="btn btn-sm mt-1 w-100"
                                            style="background:#f97316; color:#fff; font-weight:700; border-radius:6px;"
                                            onclick="selectWalkinCustomer()">
                                        👤 Quick Walk-in
                                    </button>

                                    {{-- Optional walk-in customer name input, hidden until button clicked --}}
                                    <div id="walkin-name-row" style="display:none; margin-top:8px;">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text" style="font-size:0.8rem;">Name</span>
                                            <input type="text" id="walkin-name-input"
                                                   class="form-control form-control-sm"
                                                   placeholder="Optional name..."
                                                   oninput="updateWalkinNote()">
                                        </div>
                                    </div>
                                    {{-- Hidden notes field carrying the walk-in name --}}
                                    <input type="hidden" id="walkin-notes" name="notes" value="">

                                    @error('customer_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 col-xl-4 mb-3">
                                    <label class="small mb-1" for="reference">
                                        {{ __('Reference') }}
                                    </label>

                                    <input type="text" class="form-control"
                                           id="reference"
                                           name="reference"
                                           value="ORD"
                                           readonly
                                    >

                                    @error('reference')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </form>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">{{ __('Product') }}</th>
                                            <th scope="col" class="text-center">{{ __('Quantity') }}</th>
                                            <th scope="col" class="text-center">{{ __('Price') }}</th>
                                            <th scope="col" class="text-center">{{ __('SubTotal') }}</th>
                                            <th scope="col" class="text-center">
                                                {{ __('Action') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($carts as $item)
                                        <tr>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td style="min-width: 170px;">
                                                <form></form>
                                                <form action="{{ route('pos.updateCartItem', $item->rowId) }}" method="POST">
                                                    @csrf
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" name="qty" required value="{{ old('qty', $item->qty) }}">
                                                        <input type="hidden" class="form-control" name="product_id" value="{{ $item->id }}">

                                                        <div class="input-group-append text-center">
                                                            <button type="submit" class="btn btn-icon btn-success border-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sumbit">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                {{ $item->price }}
                                            </td>
                                            <td class="text-center">
                                                {{ $item->subtotal }}
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('pos.deleteCartItem', $item->rowId) }}" method="POST">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="btn btn-icon btn-outline-danger " onclick="return confirm('Are you sure you want to delete this record?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <td colspan="5" class="text-center">
                                            {{ __('Add Products') }}
                                        </td>
                                        @endforelse

                                        <tr>
                                            <td colspan="4" class="text-end">
                                                Total Product
                                            </td>
                                            <td class="text-center">
                                                {{ Cart::count() }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end">Subtotal</td>
                                            <td class="text-center">
                                                {{ Cart::subtotal() }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end">Tax</td>
                                            <td class="text-center">
                                                {{ 0 }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">Total (Calculated)</td>
                                            <td class="text-center fw-bold">
                                                {{ Cart::total() }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end text-primary fw-bold" style="vertical-align: middle;">Final Negotiated Total (Optional)</td>
                                            <td class="text-center">
                                                <input type="number" step="0.01" name="custom_total" form="invoiceForm" class="form-control text-center text-primary fw-bold" placeholder="Leave blank to use calculated total" style="min-width: 120px;">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" form="invoiceForm" class="btn btn-success add-list mx-1 {{ Cart::count() > 0 ? '' : 'disabled' }}">
                                {{ __('Create Invoice') }}
                            </button>
                        </div>
                </div>
            </div>


            <div class="col-lg-5">
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">
                        List Product
                    </div>
                    <div class="card-body">
                        <div class="col-lg-12 mb-3">
                            <input type="text" id="productSearch" class="form-control" placeholder="Search product by name...">
                        </div>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle">
                                    <thead class="thead-light">
                                        <tr>
                                            {{--- <th scope="col">No.</th> ---}}
                                            <th scope="col">Name</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Unit</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productTableBody">
                                        @forelse ($products as $product)
                                        <tr>
                                            {{---
                                            <td>
                                                <div style="max-height: 80px; max-width: 80px;">
                                                    <img class="img-fluid"  src="{{ $product->product_image ? asset('storage/products/'.$product->product_image) : asset('assets/img/products/default.webp') }}">
                                                </div>
                                            </td>
                                            ---}}
                                            <td class="text-center">
                                                {{ $product->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->quantity }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->unit->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($product->selling_price, 2) }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <form action="{{ route('pos.addCartItem', $product) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $product->id }}">
                                                        <input type="hidden" name="name" value="{{ $product->name }}">
                                                        <input type="hidden" name="selling_price" value="{{ $product->selling_price }}">

                                                        <button type="submit" class="btn btn-icon btn-outline-primary">
                                                            <x-icon.cart/>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <th colspan="6" class="text-center" >
                                                Data not found!
                                            </th>
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

<!-- Modal for Quick Add Customer -->
<div class="modal modal-blur fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="addCustomerForm">
                <div class="modal-header">
                    <h5 class="modal-title">Quick Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="customerFormError" class="alert alert-danger d-none"></div>
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label required">Name</label>
                            <input type="text" class="form-control" name="name" required placeholder="Customer Name">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label required">Phone</label>
                            <input type="text" class="form-control" name="phone" required placeholder="Customer Phone">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Email (Optional)</label>
                            <input type="email" class="form-control" name="email" placeholder="Customer Email">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Notebook Page Number (Optional)</label>
                            <input type="text" class="form-control" name="page_number" placeholder="e.g. 21">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="btnSaveCustomer">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@pushonce('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('addCustomerForm');
            const errorAlert = document.getElementById('customerFormError');
            const btnSave = document.getElementById('btnSaveCustomer');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                errorAlert.classList.add('d-none');
                btnSave.disabled = true;
                btnSave.textContent = 'Saving...';

                const formData = new FormData(form);

                fetch("{{ route('customers.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if(data.success && data.customer) {
                        // Add to select
                        const select = document.getElementById('customer_id');
                        const option = document.createElement('option');
                        option.value = data.customer.id;
                        option.text = data.customer.name;
                        // Update TomSelect if it exists
                        let ts = select.tomselect;
                        if (ts) {
                            ts.addOption({value: data.customer.id, text: data.customer.name});
                            ts.setValue(data.customer.id);
                        } else {
                            option.selected = true;
                            select.appendChild(option);
                        }

                        // Close modal and reset
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addCustomerModal'));
                        modal.hide();
                        form.reset();
                    }
                })
                .catch(error => {
                    errorAlert.classList.remove('d-none');
                    if (error.errors) {
                        errorAlert.innerHTML = Object.values(error.errors).map(err => `<div>${err[0]}</div>`).join('');
                    } else {
                        errorAlert.innerHTML = 'An error occurred while saving.';
                    }
                })
                .finally(() => {
                    btnSave.disabled = false;
                    btnSave.textContent = 'Save Customer';
                });
            });
        });
    </script>

    <script>
        function selectWalkinCustomer() {
            const btn = document.getElementById('walkin-btn');
            const select = document.getElementById('customer_id');
            const nameRow = document.getElementById('walkin-name-row');

            btn.disabled = true;
            btn.textContent = 'Setting up...';

            fetch('{{ route("customers.walkin") }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                // Add option if not already in the dropdown
                let opt = select.querySelector(`option[value="${data.id}"]`);
                if (!opt) {
                    opt = new Option(data.name, data.id);
                    select.appendChild(opt);
                }
                // Update TomSelect if exists
                let ts = select.tomselect;
                if(ts) {
                    ts.addOption({value: data.id, text: data.name});
                    ts.setValue(data.id);
                } else {
                    select.value = data.id;
                }
                
                // Save to session storage
                sessionStorage.setItem('pos_customer_id', data.id);
                
                btn.textContent = '✅ Walk-in Customer Selected';
                btn.style.background = '#16a34a';
                // Show the optional name input
                nameRow.style.display = 'block';
                document.getElementById('walkin-name-input').focus();
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = '👤 Quick Cash Sale (Walk-in Customer)';
                btn.style.background = '#f97316';
                alert('Could not set walk-in customer. Please select manually.');
            });
        }

        function updateWalkinNote() {
            const name = document.getElementById('walkin-name-input').value.trim();
            const notesField = document.getElementById('walkin-notes');
            notesField.value = name ? 'Walk-in: ' + name : '';
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Customer Search Dropdown
            let customerSelect = new TomSelect("#customer_id", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });

            // Restore state from sessionStorage
            let savedCustomer = sessionStorage.getItem('pos_customer_id');
            if (savedCustomer) {
                customerSelect.setValue(savedCustomer);
            }
            
            let savedWalkinName = sessionStorage.getItem('pos_walkin_name');
            if (savedWalkinName) {
                document.getElementById('walkin-name-input').value = savedWalkinName;
                document.getElementById('walkin-notes').value = 'Walk-in: ' + savedWalkinName;
                document.getElementById('walkin-name-row').style.display = 'block';
            }

            // Save state on change
            customerSelect.on('change', function(val) {
                sessionStorage.setItem('pos_customer_id', val);
            });

            document.getElementById('walkin-name-input').addEventListener('input', function(e) {
                sessionStorage.setItem('pos_walkin_name', e.target.value);
            });

            // Clear session storage on successful invoice creation
            document.getElementById('invoiceForm').addEventListener('submit', function() {
                sessionStorage.removeItem('pos_customer_id');
                sessionStorage.removeItem('pos_walkin_name');
            });

            // Product List Search
            document.getElementById('productSearch').addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#productTableBody tr');
                rows.forEach(row => {
                    let text = row.querySelector('td:first-child').textContent.toLowerCase();
                    if(text.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endpushonce
