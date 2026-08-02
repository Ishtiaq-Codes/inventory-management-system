<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use App\Mail\StockAlert;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->count();

        return view('orders.index', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        $products = Product::where('user_id', auth()->id())->with(['category', 'unit'])->get();

        $customers = Customer::where('user_id', auth()->id())->get(['id', 'name']);

        $carts = Cart::content();

        return view('orders.create', [
            'products' => $products,
            'customers' => $customers,
            'carts' => $carts,
        ]);
    }

    public function store(OrderStoreRequest $request)
    {
        $stockError = DB::transaction(function () use ($request) {
            // Create Order Details and Reduce Stock
            $contents = Cart::content();

            // --- STEP 1: Pre-validate ALL stock before creating anything ---
            foreach ($contents as $content) {
                $productEntity = Product::lockForUpdate()->find($content->id);
                if (!$productEntity || $productEntity->quantity < $content->qty) {
                    return 'Not enough stock for: ' . ($productEntity->name ?? 'Unknown product') . '. Available: ' . ($productEntity->quantity ?? 0);
                }
            }

            // --- STEP 2: All stock OK, now create the order ---
            $finalTotal = $request->filled('custom_total') ? str_replace(',', '', $request->custom_total) : str_replace(',', '', Cart::total());

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'payment_type' => $request->payment_type,
                'pay' => $request->pay,
                'order_date' => $request->order_date ?? Carbon::now()->format('Y-m-d'),
                'order_status' => OrderStatus::COMPLETE->value,
                'total_products' => Cart::count(),
                'sub_total' => Cart::subtotal(),
                'vat' => 0,
                'total' => $finalTotal,
                'invoice_no' => IdGenerator::generate([
                    'table' => 'orders',
                    'field' => 'invoice_no',
                    'length' => 10,
                    'prefix' => 'INV-'
                ]),
                'due' => ($finalTotal - $request->pay),
                'user_id' => auth()->id(),
                'uuid' => Str::uuid(),
                'notes' => $request->notes,
            ]);

            $oDetails = [];
            $stockAlertProducts = [];

            foreach ($contents as $content) {
                $oDetails['order_id'] = $order['id'];
                $oDetails['product_id'] = $content->id;
                $oDetails['quantity'] = $content->qty;
                $oDetails['unitcost'] = $content->price;
                $oDetails['total'] = $content->subtotal;
                $oDetails['created_at'] = Carbon::now();

                OrderDetails::insert($oDetails);

                // Reduce the stock
                $productEntity = Product::where('id', $content->id)->first();
                $newQty = $productEntity->quantity - $content->qty;
                if ($newQty < $productEntity->quantity_alert) {
                    $stockAlertProducts[] = $productEntity;
                }
                $productEntity->update(['quantity' => $newQty]);
            }

            if (count($stockAlertProducts) > 0) {
                $listAdmin = [];
                foreach (User::all('email') as $admin) {
                    $listAdmin [] = $admin->email;
                }
                Mail::to($listAdmin)->send(new StockAlert($stockAlertProducts));
            }

            // Clear cart
            Cart::destroy();

            return $order;
        });

        if (is_string($stockError)) {
            return redirect()->back()->with('error', $stockError);
        }

        return redirect()
            ->route('orders.show', $stockError->uuid)
            ->with('success', 'Order has been created!');
    }

    public function show($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        $order->loadMissing(['customer', 'details'])->get();
        return view('orders.show', [
            'order' => $order
        ]);
    }

    public function update($uuid, Request $request)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        // TODO refactoring

        $order->update([
            'order_status' => OrderStatus::COMPLETE,
            // 'due' => '0',
            // 'pay' => $order->total
        ]);

        return redirect()
            ->route('orders.complete')
            ->with('success', 'Order has been completed!');
    }

    public function destroy($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        
        $restoredItems = [];
        // Restore product stock
        $orderDetails = OrderDetails::where('order_id', $order->id)->get();
        foreach ($orderDetails as $detail) {
            $product = Product::find($detail->product_id);
            if ($product) {
                $product->increment('quantity', $detail->quantity);
                $restoredItems[] = $product->name . ' (+' . $detail->quantity . ')';
            }
        }

        // Delete order details
        OrderDetails::where('order_id', $order->id)->delete();
        
        // Delete the order itself
        $order->delete();

        $message = 'Order has been successfully deleted.';
        if (count($restoredItems) > 0) {
            $message .= ' Stock restored for: ' . implode(', ', $restoredItems);
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }

    public function downloadInvoice($uuid)
    {
        $order = Order::with(['customer', 'details'])->where('uuid', $uuid)->firstOrFail();
        // TODO: Need refactor
        //dd($order);

        //$order = Order::with('customer')->where('id', $order_id)->first();
        // $order = Order::
        //     ->where('id', $order)
        //     ->first();

        return view('orders.print-invoice', [
            'order' => $order,
        ]);
    }

    public function cancel(Order $order)
    {
        $order->update([
            'order_status' => OrderStatus::CANCEL
        ]);
        $orders = Order::where('user_id',auth()->id())->count();

        return redirect()
            ->route('orders.index', [
                'orders' => $orders
            ])
            ->with('success', 'Order has been canceled!');
    }
}
