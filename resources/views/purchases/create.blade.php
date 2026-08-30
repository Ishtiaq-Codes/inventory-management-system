@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <x-alert/>

        <div class="row row-cards">

            <form action="{{ route('purchases.store') }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h3 class="card-title">
                                        {{ __('Create Purchase') }}
                                    </h3>
                                </div>

                                <div class="card-actions btn-actions">
                                    {{--- {{ URL::previous() }} ---}}
                                    <a href="{{ route('purchases.index') }}" class="btn-action">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="row gx-3 mb-3">
                                    <div class="col-12 col-md-6 col-xl-4 mb-3">
                                        <label for="date" class="form-label required">
                                            {{ __('Purchase Date') }}
                                        </label>

                                        <input name="date" id="date" type="date"
                                               class="form-control example-date-input

                                               @error('date') is-invalid @enderror"
                                               value="{{ old('date') ?? now()->format('Y-m-d') }}"
                                               required
                                        >

                                        @error('date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>


                                    <div class="col-12 col-md-6 col-xl-4 mb-3">
                                        <label class="form-label required" for="supplier_id">
                                            {{ __('Supplier') }}
                                        </label>

                                        <div class="input-group">
                                            <select class="form-select form-control-solid @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                                <option selected="" disabled="">
                                                    Select a supplier:
                                                </option>

                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" @selected( old('supplier_id') == $supplier->id)>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSupplierModal" title="Add New Supplier">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                                            </button>
                                        </div>

                                        @error('supplier_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 col-xl-4 mb-3">
                                        <label for="reference" class="form-label required">
                                            {{ __('Reference') }}
                                        </label>

                                        <input type="text" class="form-control"
                                               id="reference"
                                               name="reference"
                                               value="PRS"
                                               readonly
                                        >

                                        @error('reference')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                @livewire('purchase-form')
                            </div>

                            <div class="card-footer text-end">
                                {{--- onclick="return confirm('Are you sure you want to purchase?')" ---}}
                                {{--- @disabled($errors->isNotEmpty()) ---}}
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Purchase') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Quick Add Supplier -->
<div class="modal modal-blur fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="addSupplierForm">
                <div class="modal-header">
                    <h5 class="modal-title">Quick Add Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="supplierFormError" class="alert alert-danger d-none"></div>
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label required">Name</label>
                            <input type="text" class="form-control" name="name" required placeholder="Supplier Name">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Supplier Email">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label required">Phone</label>
                            <input type="text" class="form-control" name="phone" required placeholder="Supplier Phone">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label required">Shop Name</label>
                            <input type="text" class="form-control" name="shopname" required placeholder="Shop Name">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label required">Type</label>
                            <select class="form-select" name="type" required>
                                @foreach(\App\Enums\SupplierType::cases() as $supplierType)
                                    <option value="{{ $supplierType->value }}">{{ $supplierType->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label required">Address</label>
                            <input type="text" class="form-control" name="address" required placeholder="Supplier Address">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="btnSaveSupplier">Save Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@pushonce('page-scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('addSupplierForm');
        const errorAlert = document.getElementById('supplierFormError');
        const btnSave = document.getElementById('btnSaveSupplier');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            errorAlert.classList.add('d-none');
            btnSave.disabled = true;
            btnSave.textContent = 'Saving...';

            const formData = new FormData(form);

            fetch("{{ route('suppliers.store') }}", {
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
                if(data.success && data.supplier) {
                    // Add to select
                    const select = document.getElementById('supplier_id');
                    const option = document.createElement('option');
                    option.value = data.supplier.id;
                    option.text = data.supplier.name;
                    option.selected = true;
                    select.appendChild(option);

                    // Close modal and reset
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
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
                btnSave.textContent = 'Save Supplier';
            });
        });
    });
</script>
@endpushonce
