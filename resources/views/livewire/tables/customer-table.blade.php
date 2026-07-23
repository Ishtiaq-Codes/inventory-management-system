<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                {{ __('Customers') }}
            </h3>
        </div>

        <div class="card-actions d-flex align-items-center gap-2">
            <a href="{{ route('customers.import.create') }}" class="btn btn-outline-primary d-none d-sm-flex align-items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-upload m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path><path d="M7 9l5 -5l5 5"></path><path d="M12 4l0 12"></path></svg>
                Import Customers
            </a>
            <x-action.create route="{{ route('customers.create') }}" />
        </div>
    </div>

    <div class="card-body border-bottom py-3">
        <div class="d-flex align-items-center">
            <div class="text-secondary">
                Show
                <div class="mx-2 d-inline-block">
                    <select wire:model.live="perPage" class="form-select form-select-sm" aria-label="result per page">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                    </select>
                </div>
                entries
            </div>
            
            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <span class="text-secondary me-2">From:</span>
                    <input type="date" wire:model.live="startDate" class="form-control form-control-sm" title="Start Date">
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-secondary me-2">To:</span>
                    <input type="date" wire:model.live="endDate" class="form-control form-control-sm" title="End Date">
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-secondary me-2">Search:</span>
                    <input type="text" wire:model.live="search" class="form-control form-control-sm" aria-label="Search invoice">
                </div>
            </div>
        </div>
    </div>

    <x-spinner.loading-spinner/>

    <div class="table-responsive">
        <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
            <thead class="thead-light">
            <tr>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('id')" href="#" role="button">
                        {{ __('Id') }}
                        @include('inclues._sort-icon', ['field' => 'id'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('name')" href="#" role="button">
                        {{ __('Name') }}
                        @include('inclues._sort-icon', ['field' => 'name'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('email')" href="#" role="button">
                        {{ __('Email') }}
                        @include('inclues._sort-icon', ['field' => 'email'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('orders_count')" href="#" role="button">
                        {{ __('Order Counts') }}
                        @include('inclues._sort-icon', ['field' => 'orders_count'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                        {{ __('Paid Amount') }}
                        @include('inclues._sort-icon', ['field' => 'orders_sum_pay'])
                </th>
                <th scope="col" class="align-middle text-center">
                        {{ __('Due Amount') }}
                        @include('inclues._sort-icon', ['field' => 'orders_sum_due'])
                </th>
                <th scope="col" class="align-middle text-center">
                    {{ __('Total Amount') }}
                    @include('inclues._sort-icon', ['field' => 'orders_sum_sub_total'])
            </th>
            {{-- <th scope="col" class="align-middle text-center">
                {{ __('Extra Amount') }}
                @include('inclues._sort-icon', ['field' => 'orders_sum_vat'])
        </th> --}}
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('created_at')" href="#" role="button">
                        {{ __('Created at') }}
                        @include('inclues._sort-icon', ['field' => 'Created_at'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    {{ __('Action') }}
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td class="align-middle text-center">
                        {{ $loop->index }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $customer->name }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $customer->email }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $customer->orders_count }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $customer->orders_sum_pay ?? 0 }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $customer->orders_sum_due ?? 0 }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $customer->orders_sum_sub_total ?? 0 }}
                    </td>
                    {{-- <td class="align-middle text-center">
                        {{ $customer->orders_sum_vat ?? 0 }}
                    </td> --}}
                    <td class="align-middle text-center">
                        {{ $customer->created_at->diffForHumans() }}
                    </td>
                    <td class="align-middle text-center">
                        <x-button.show class="btn-icon" route="{{ route('customers.show', $customer->uuid) }}"/>
                        <x-button.edit class="btn-icon" route="{{ route('customers.edit', $customer->uuid) }}"/>
                        <x-button.delete
                            class="btn-icon"
                            route="{{ route('customers.destroy', $customer->uuid) }}"
                            onclick="return confirm('Are you sure to remove {{ $customer->name }} ?')"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="align-middle text-center" colspan="8">
                        No results found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $customers->firstItem() }}</span> to <span>{{ $customers->lastItem() }}</span> of <span>{{ $customers->total() }}</span> entries
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $customers->links() }}
        </ul>
    </div>
</div>
