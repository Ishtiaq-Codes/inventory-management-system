@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <x-alert/>

        <div class="row row-cards">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    {{ __('Product Image') }}
                                </h3>

                                <img class="img-account-profile mb-2" src="{{ asset('assets/img/products/default.webp') }}" alt="" id="image-preview" />

                                <div class="small font-italic text-muted mb-2">
                                    JPG or PNG no larger than 2 MB
                                </div>

                                <input
                                    type="file"
                                    accept="image/*"
                                    id="image"
                                    name="product_image"
                                    class="form-control @error('product_image') is-invalid @enderror"
                                    onchange="previewImage();"
                                >

                                @error('product_image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h3 class="card-title">
                                        {{ __('Product Create') }}
                                    </h3>
                                </div>

                                <div class="card-actions">
                                    <a href="{{ route('products.index') }}" class="btn-action">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-cards">
                                    <div class="col-md-12">

                                        <x-input name="name"
                                                 id="name"
                                                 placeholder="Product name"
                                                 value="{{ old('name') }}"
                                        />
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label d-flex justify-content-between align-items-center">
                                                <span>
                                                    Product category
                                                    <span class="text-danger">*</span>
                                                </span>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal-category" class="text-primary text-decoration-none small" style="font-size: 13px;">
                                                    + Add New
                                                </a>
                                            </label>

                                            @if ($categories->count() === 1)
                                                <select name="category_id" id="category_id"
                                                        class="form-select @error('category_id') is-invalid @enderror"
                                                        readonly
                                                >
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" selected>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select name="category_id" id="category_id"
                                                        class="form-select @error('category_id') is-invalid @enderror"
                                                        required
                                                >
                                                    <option value="" selected="" disabled="">
                                                        Select a category:
                                                    </option>

                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" @if(old('category_id') == $category->id) selected="selected" @endif>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif

                                            @error('category_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label d-flex justify-content-between align-items-center" for="unit_id">
                                                <span>
                                                    {{ __('Unit') }}
                                                    <span class="text-danger">*</span>
                                                </span>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal-unit" class="text-primary text-decoration-none small" style="font-size: 13px;">
                                                    + Add New
                                                </a>
                                            </label>

                                            @if ($units->count() === 1)
                                                <select name="unit_id" id="unit_id"
                                                        class="form-select @error('unit_id') is-invalid @enderror"
                                                        readonly
                                                >
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}" selected>
                                                            {{ $unit->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select name="unit_id" id="unit_id"
                                                        class="form-select @error('unit_id') is-invalid @enderror"
                                                        required
                                                >
                                                    <option value="" selected="" disabled="">
                                                        Select a unit:
                                                    </option>

                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}" @if(old('unit_id') == $unit->id) selected="selected" @endif>{{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                            @endif

                                            @error('unit_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <x-input type="number"
                                                 label="Buying Price"
                                                 name="buying_price"
                                                 id="buying_price"
                                                 placeholder="0"
                                                 value="{{ old('buying_price') }}"
                                        />
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <x-input type="number"
                                                 label="Selling Price"
                                                 name="selling_price"
                                                 id="selling_price"
                                                 placeholder="0"
                                                 value="{{ old('selling_price') }}"
                                        />
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <x-input type="number"
                                                 label="Quantity"
                                                 name="quantity"
                                                 id="quantity"
                                                 placeholder="0"
                                                 value="{{ old('quantity') }}"
                                        />
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <x-input type="number"
                                                 label="Quantity Alert"
                                                 name="quantity_alert"
                                                 id="quantity_alert"
                                                 placeholder="0"
                                                 value="{{ old('quantity_alert') }}"
                                        />
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <x-input type="number"
                                                 label="Tax"
                                                 name="tax"
                                                 id="tax"
                                                 placeholder="0"
                                                 value="{{ old('tax') }}"
                                        />
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="tax_type">
                                                {{ __('Tax Type') }}
                                            </label>

                                            <select name="tax_type" id="tax_type"
                                                    class="form-select @error('tax_type') is-invalid @enderror"
                                            >
                                                <option value="" selected="" disabled="">Select a tax type:</option>
                                                @foreach(\App\Enums\TaxType::cases() as $taxType)
                                                <option value="{{ $taxType->value }}" @selected(old('tax_type') == $taxType->value)>
                                                    {{ $taxType->label() }}
                                                </option>
                                                @endforeach
                                            </select>

                                            @error('tax_type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="notes" class="form-label">
                                                {{ __('Notes') }}
                                            </label>

                                            <textarea name="notes"
                                                      id="notes"
                                                      rows="5"
                                                      class="form-control @error('notes') is-invalid @enderror"
                                                      placeholder="Product notes"
                                            ></textarea>

                                            @error('notes')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <x-button.save type="submit">
                                    {{ __('Save') }}
                                </x-button.save>

                                <a class="btn btn-warning" href="{{ url()->previous() }}">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div class="modal modal-blur fade" id="modal-category" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form-category">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" class="form-control" name="name" id="new_category_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn-save-category">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Unit Modal -->
<div class="modal modal-blur fade" id="modal-unit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form-unit">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Unit Name</label>
                        <input type="text" class="form-control" name="name" id="new_unit_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Short Code</label>
                        <input type="text" class="form-control" name="short_code" id="new_unit_short_code" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn-save-unit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@pushonce('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Category AJAX
        const formCategory = document.getElementById('form-category');
        formCategory.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-save-category');
            btn.disabled = true;
            btn.innerHTML = 'Saving...';

            fetch('{{ route("categories.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: document.getElementById('new_category_name').value
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.category) {
                    const select = document.getElementById('category_id');
                    const option = new Option(data.category.name, data.category.id, true, true);
                    select.add(option);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modal-category'));
                    modal.hide();
                    formCategory.reset();
                } else {
                    alert('Error saving category (Validation failed)');
                }
            })
            .catch(error => {
                alert('Error saving category');
                console.error(error);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Save';
            });
        });

        // Unit AJAX
        const formUnit = document.getElementById('form-unit');
        formUnit.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-save-unit');
            btn.disabled = true;
            btn.innerHTML = 'Saving...';

            fetch('{{ route("units.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: document.getElementById('new_unit_name').value,
                    short_code: document.getElementById('new_unit_short_code').value
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.unit) {
                    const select = document.getElementById('unit_id');
                    const option = new Option(data.unit.name, data.unit.id, true, true);
                    select.add(option);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modal-unit'));
                    modal.hide();
                    formUnit.reset();
                } else {
                    alert('Error saving unit (Validation failed)');
                }
            })
            .catch(error => {
                alert('Error saving unit');
                console.error(error);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Save';
            });
        });
    });
    </script>
@endpushonce
