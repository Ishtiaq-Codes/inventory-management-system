@extends('layouts.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center mb-3">
                <div class="col">
                    <h2 class="page-title">
                        {{ __('Edit Product') }}
                    </h2>
                </div>
            </div>

            @include('partials._breadcrumbs', ['model' => $product])
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">

                <form action="{{ route('products.update', $product->uuid) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        {{ __('Product Image') }}
                                    </h3>

                                    <img class="img-account-profile mb-2"
                                        src="{{ $product->product_image ? asset('storage/' . $product->product_image) : asset('assets/img/products/default.webp') }}"
                                        alt="" id="image-preview">

                                    <div class="small font-italic text-muted mb-2">
                                        JPG or PNG no larger than 2 MB
                                    </div>

                                    <input type="file" accept="image/*" id="image" name="product_image"
                                        class="form-control @error('product_image') is-invalid @enderror"
                                        onchange="previewImage();">

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
                                <div class="card-body">
                                    <h3 class="card-title">
                                        {{ __('Product Details') }}
                                    </h3>

                                    <div class="row row-cards">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">
                                                    {{ __('Name') }}
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="text" id="name" name="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    placeholder="Product name" value="{{ old('name', $product->name) }}">

                                                @error('name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="category_id" class="form-label d-flex justify-content-between">
                                                    <span>Product category <span class="text-danger">*</span></span>
                                                    <a href="javascript:void(0);" onclick="toggleCategoryCreate()" class="text-decoration-none small">+ Add new</a>
                                                </label>

                                                <div id="category_select_container">
                                                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                                        <option value="" selected="" disabled="">Select a category:</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                @if (old('category_id', $product->category_id) == $category->id) selected="selected" @endif>
                                                                {{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div id="category_create_container" style="display: none;" class="mt-2">
                                                    <div class="input-group">
                                                        <input type="text" id="new_category_name" class="form-control" placeholder="New category name">
                                                        <button type="button" class="btn btn-primary" onclick="createCategory()">Save</button>
                                                        <button type="button" class="btn btn-secondary" onclick="toggleCategoryCreate()">Cancel</button>
                                                    </div>
                                                </div>

                                                @error('category_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label d-flex justify-content-between" for="unit_id">
                                                    <span>{{ __('Unit') }} <span class="text-danger">*</span></span>
                                                    <a href="javascript:void(0);" onclick="toggleUnitCreate()" class="text-decoration-none small">+ Add new</a>
                                                </label>

                                                <div id="unit_select_container">
                                                    <select name="unit_id" id="unit_id"
                                                        class="form-select @error('unit_id') is-invalid @enderror" required>
                                                        <option value="" selected="" disabled="">
                                                            Select a unit:
                                                        </option>

                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}"
                                                                @if (old('unit_id', $product->unit_id) == $unit->id) selected="selected" @endif>
                                                                {{ $unit->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div id="unit_create_container" style="display: none;" class="mt-2">
                                                    <div class="input-group">
                                                        <input type="text" id="new_unit_name" class="form-control" placeholder="New unit name">
                                                        <button type="button" class="btn btn-primary" onclick="createUnit()">Save</button>
                                                        <button type="button" class="btn btn-secondary" onclick="toggleUnitCreate()">Cancel</button>
                                                    </div>
                                                </div>

                                                @error('unit_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="buying_price">
                                                    Buying price
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="text" id="buying_price" name="buying_price"
                                                    class="form-control @error('buying_price') is-invalid @enderror"
                                                    placeholder="0"
                                                    value="{{ old('buying_price', $product->buying_price) }}">

                                                @error('buying_price')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="selling_price" class="form-label">
                                                    Selling price
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="text" id="selling_price" name="selling_price"
                                                    class="form-control @error('selling_price') is-invalid @enderror"
                                                    placeholder="0"
                                                    value="{{ old('selling_price', $product->selling_price) }}">

                                                @error('selling_price')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="quantity" class="form-label">
                                                    {{ __('Quantity') }}
                                                </label>

                                                <input class="form-control" name="quantity" type="text" readonly value="{{ old('quantity', $product->quantity) }}"  required="true" aria-required="true" style="color: var(--tblr-secondary);background-color: var(--tblr-bg-surface-secondary); opacity: 1;"/>


                                                {{-- <input type="text" id="quantity" name="quantity"
                                                    class="form-control"
                                                    min="0" value="{{ old('quantity', $product->quantity) }}"
                                                    placeholder="0" disabled > --}}
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="quantity_alert" class="form-label">
                                                    {{ __('Quantity Alert') }}
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="number" id="quantity_alert" name="quantity_alert"
                                                    class="form-control @error('quantity_alert') is-invalid @enderror"
                                                    min="0" placeholder="0"
                                                    value="{{ old('quantity_alert', $product->quantity_alert) }}">

                                                @error('quantity_alert')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="tax" class="form-label">
                                                    {{ __('Tax') }}
                                                </label>

                                                <input type="number" id="tax" name="tax"
                                                    class="form-control @error('tax') is-invalid @enderror"
                                                    min="0" placeholder="0"
                                                    value="{{ old('tax', $product->tax) }}">

                                                @error('tax')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="tax_type">
                                                    {{ __('Tax Type') }}
                                                </label>

                                                <select name="tax_type" id="tax_type"
                                                    class="form-select @error('tax_type') is-invalid @enderror">
                                                    <option value="" disabled="">Select a tax type:</option>
                                                    @foreach (\App\Enums\TaxType::cases() as $taxType)
                                                        <option value="{{ $taxType->value }}"
                                                            @selected(old('tax_type', $product->tax_type) == $taxType->value)>
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
                                            <div class="mb-3 mb-0">
                                                <label for="notes" class="form-label">
                                                    {{ __('Notes') }}
                                                </label>

                                                <textarea name="notes" id="notes" rows="5" class="form-control @error('notes') is-invalid @enderror"
                                                    placeholder="Product notes">{{ old('notes', $product->notes) }}</textarea>

                                                @error('notes')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>`
                                    </div>
                                </div>

                                <div class="card-footer text-end">
                                    <button class="btn btn-primary" type="submit">
                                        {{ __('Update') }}
                                    </button>

                                    <a class="btn btn-danger" href="{{ url()->previous() }}">
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
@endsection

@pushonce('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
    <script>
        function toggleCategoryCreate() {
            const selectContainer = document.getElementById('category_select_container');
            const createContainer = document.getElementById('category_create_container');
            if (createContainer.style.display === 'none') {
                selectContainer.style.display = 'none';
                createContainer.style.display = 'block';
            } else {
                selectContainer.style.display = 'block';
                createContainer.style.display = 'none';
            }
        }

        function createCategory() {
            const name = document.getElementById('new_category_name').value;
            if (!name) return alert('Please enter a category name');

            fetch('{{ route('categories.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ name: name })
            })
            .then(response => response.json())
            .then(data => {
                if (data.category) {
                    const select = document.getElementById('category_id');
                    const option = document.createElement('option');
                    option.value = data.category.id;
                    option.text = data.category.name;
                    option.selected = true;
                    select.appendChild(option);
                    document.getElementById('new_category_name').value = '';
                    toggleCategoryCreate();
                } else if (data.message) {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function toggleUnitCreate() {
            const selectContainer = document.getElementById('unit_select_container');
            const createContainer = document.getElementById('unit_create_container');
            if (createContainer.style.display === 'none') {
                selectContainer.style.display = 'none';
                createContainer.style.display = 'block';
            } else {
                selectContainer.style.display = 'block';
                createContainer.style.display = 'none';
            }
        }

        function createUnit() {
            const name = document.getElementById('new_unit_name').value;
            if (!name) return alert('Please enter a unit name');
            const shortCode = name.substring(0, 2).toUpperCase();

            fetch('{{ route('units.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ name: name, short_code: shortCode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.unit) {
                    const select = document.getElementById('unit_id');
                    const option = document.createElement('option');
                    option.value = data.unit.id;
                    option.text = data.unit.name;
                    option.selected = true;
                    select.appendChild(option);
                    document.getElementById('new_unit_name').value = '';
                    toggleUnitCreate();
                } else if (data.message) {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endpushonce
