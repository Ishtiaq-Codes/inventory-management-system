@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <form action="{{ route('customers.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Import Old Register (Customers & Debt)') }}
                    </x-slot:title>

                    <x-slot:actions>
                        <a href="{{ asset('assets/templates/customer_import_template.xlsx') }}" download class="btn btn-outline-success mx-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path><path d="M7 11l5 5l5 -5"></path><path d="M12 4l0 12"></path></svg>
                            Download Blank Template
                        </a>
                        <x-action.close route="{{ route('customers.index') }}" />
                    </x-slot:actions>
                </x-slot:header>

                <x-slot:content>
                    <div class="alert alert-info" role="alert">
                        <div class="d-flex">
                            <div>
                                <!-- Download SVG icon from http://tabler-icons.io/i/info-circle -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="12" r="9"></circle><line x1="12" y1="8" x2="12.01" y2="8"></line><polyline points="11 12 12 12 12 16 13 16"></polyline></svg>
                            </div>
                            <div>
                                <h4 class="alert-title">Instructions</h4>
                                <div class="text-secondary">
                                    Please use the official template. Columns must be exactly:
                                    <br><b>A:</b> Name | <b>B:</b> Phone | <b>C:</b> Address | <b>D:</b> Type (Retail/Wholesale) | <b>E:</b> Shop Name | <b>F:</b> Total Old Bill | <b>G:</b> Paid Amount | <b>H:</b> Page Number (Optional)
                                </div>
                            </div>
                        </div>
                    </div>

                    <label class="form-label required">Upload filled Excel File (.xlsx, .csv)</label>
                    <input type="file"
                           id="file"
                           name="file"
                           class="form-control @error('file') is-invalid @enderror"
                           accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                           required
                    >

                    @error('file')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <x-button type="submit" class="btn-primary">
                        {{ __('Upload & Import Data') }}
                    </x-button>
                </x-slot:footer>
            </x-card>
        </form>
    </div>
</div>
@endsection
