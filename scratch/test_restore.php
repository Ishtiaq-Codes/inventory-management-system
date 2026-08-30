<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$order = \App\Models\Order::latest()->first();
echo "Latest order ID: " . $order->id . "\n";

$orderDetails = \App\Models\OrderDetails::where('order_id', $order->id)->get();
foreach ($orderDetails as $detail) {
    $product = \App\Models\Product::find($detail->product_id);
    if ($product) {
        echo "Product ID: {$product->id}, Old Qty: {$product->quantity}\n";
        $product->update([
            'quantity' => $product->quantity + $detail->quantity
        ]);
        
        $product->refresh();
        echo "Product ID: {$product->id}, New Qty: {$product->quantity} (Added {$detail->quantity})\n";
    }
}
