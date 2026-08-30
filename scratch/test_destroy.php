<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Fake a request to the destroy method
$controller = new \App\Http\Controllers\Order\OrderController();

$order = \App\Models\Order::latest()->first();
echo "Attempting to destroy order UUID: " . $order->uuid . "\n";

try {
    $response = $controller->destroy($order->uuid);
    echo "Destroy completed. Response class: " . get_class($response) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
