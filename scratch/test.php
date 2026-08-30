<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$order = \App\Models\Order::latest()->first();
echo "Latest order ID: " . $order->id . "\n";
echo "Details count: " . \App\Models\OrderDetails::where('order_id', $order->id)->count() . "\n";

$details = \App\Models\OrderDetails::where('order_id', $order->id)->get();
foreach ($details as $detail) {
    echo "Product ID: " . $detail->product_id . " | Qty: " . $detail->quantity . "\n";
}
