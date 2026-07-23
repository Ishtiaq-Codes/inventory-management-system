<?php

namespace App\Http\Controllers\Order;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Mail\StockAlert;
use Illuminate\Http\Request;

class DueOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('due', '>', '0')
            ->latest()
            ->with('customer')
            ->get();

        return view('due.index', [
            'orders' => $orders
        ]);
    }

    public function show(Order $order)
    {
        $order->loadMissing(['customer', 'details'])->get();

        return view('due.show', [
           'order' => $order
        ]);
    }

    public function edit(Order $order)
    {
        $order->loadMissing(['customer', 'details'])->get();

        $customers = Customer::select(['id', 'name'])->get();

        return view('due.edit', [
            'order' => $order,
            'customers' => $customers
        ]);
    }

    public function update(Order $order, Request $request)
    {
        $rules = [
            'pay' => ['required', 'numeric', 'min:1', 'max:' . $order->due]
        ];

        $validatedData = $request->validate($rules);

        $mainPay = $order->pay;
        $mainDue = $order->due;

        $paidDue = $mainDue - $validatedData['pay'];
        $paidPay = $mainPay + $validatedData['pay'];

        // Safety clamp — never allow negative due
        if ($paidDue < 0) {
            $paidDue = 0;
        }

        $order->update([
            'due' => $paidDue,
            'pay' => $paidPay
        ]);

        // no more due — mark order as complete
        if ($paidDue == 0) {
            $order->update([
                'order_status' => 1
            ]);
        }

        return redirect()
            ->route('due.index')
            ->with('success', 'Due amount has been updated!');
    }
}
