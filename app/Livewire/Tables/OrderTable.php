<?php

namespace App\Livewire\Tables;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderTable extends Component
{
    use WithPagination;

    public $perPage = 5;

    public $search = '';

    public $sortField = 'created_at';

    public $sortAsc = false;

    public $startDate = '';

    public $endDate = '';

    public function sortBy($field): void
    {
        if($this->sortField === $field)
        {
            $this->sortAsc = ! $this->sortAsc;

        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function render()
    {
        return view('livewire.tables.order-table', [
            'orders' => Order::where("user_id",auth()->id())
                ->with(['customer', 'details'])
                ->when($this->startDate, fn($q) => $q->whereDate('order_date', '>=', $this->startDate))
                ->when($this->endDate, fn($q) => $q->whereDate('order_date', '<=', $this->endDate))
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }
}
