@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">
                            {{ __('Purchase Details') }}
                        </h3>
                    </div>

                    <div class="card-actions btn-actions">
                        <x-action.close route="{{ route('suppliers.show', $purchase->supplier->uuid) }}" />
                    </div>
                </div>

                <div class="card-body">
                    <div class="row row-cards mb-3">
                        <div class="col">
                            <label for="order_date" class="form-label required">
                                {{ __('Purchase Date') }}
                            </label>
                            <input type="text" id="order_date" class="form-control"
                                value="{{ $purchase->date ? $purchase->date->format('d-m-Y') : '' }}" disabled>
                        </div>

                        <div class="col">
                            <label for="invoice_no" class="form-label required">
                                {{ __('Purchase No.') }}
                            </label>
                            <input type="text" id="invoice_no" class="form-control" value="{{ $purchase->purchase_no }}"
                                disabled>
                        </div>

                        <div class="col">
                            <label for="customer" class="form-label required">
                                {{ __('Supplier') }}
                            </label>
                            <input type="text" id="customer" class="form-control" value="{{ $purchase->supplier->name }}" disabled>
                        </div>

                        <div class="col">
                            <label for="payment_type" class="form-label required">
                                {{ __('Status') }}
                            </label>

                            <input type="text" id="payment_type" class="form-control" value="{{ $purchase->status->label() }}"
                                disabled>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="align-middle text-center">No.</th>
                                    <th scope="col" class="align-middle text-center">Product Name</th>
                                    <th scope="col" class="align-middle text-center">Product Code</th>
                                    <th scope="col" class="align-middle text-center">Quantity</th>
                                    <th scope="col" class="align-middle text-center">Unit Cost</th>
                                    <th scope="col" class="align-middle text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchase->details as $item)
                                    <tr>
                                        <td class="align-middle text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ $item->product->name ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ $item->product->code ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ number_format($item->unitcost, 2) }}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ number_format($item->total, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No products found for this purchase. (Possibly a Payment or Old Balance record)</td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td colspan="5" class="text-end">
                                        <strong>Total Amount</strong>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ Number::currency($purchase->total_amount, 'PKR') }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end">
                                        <strong>Paid Amount</strong>
                                    </td>
                                    <td class="text-center text-success">
                                        <strong>{{ Number::currency($purchase->paid_amount, 'PKR') }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end">
                                        <strong>Due Balance</strong>
                                    </td>
                                    <td class="text-center text-danger">
                                        <strong>{{ Number::currency($purchase->total_amount - $purchase->paid_amount, 'PKR') }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
