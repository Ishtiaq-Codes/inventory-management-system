<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->invoice_no }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
            background-color: #f6f6f6;
            font-size: 12px;
            color: #000;
        }
        .receipt-container {
            width: 80mm;
            max-width: 100%;
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .brand-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .store-info {
            font-size: 12px;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .order-info {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .order-info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            padding: 4px 0;
        }
        th {
            border-bottom: 1px dashed #000;
            border-top: 1px dashed #000;
            text-align: left;
        }
        .totals {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
        }
        .totals p {
            margin: 4px 0;
            display: flex;
            justify-content: space-between;
        }
        .totals p.bold {
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        
        /* Print Styles */
        @media print {
            body {
                background: #fff;
                margin: 0;
                padding: 0;
            }
            .receipt-container {
                width: 100%;
                margin: 0;
                padding: 0 5mm; /* Add safe margin for thermal printers */
                box-shadow: none;
                box-sizing: border-box;
            }
            .d-print-none {
                display: none !important;
            }
            @page {
                margin: 0;
            }
        }

        .btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #000;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }
        .btn-secondary {
            background-color: #666;
        }
    </style>
</head>
<body>

    <div class="receipt-container">
        
        <!-- Action Buttons (Hidden on Print) -->
        <div class="text-center d-print-none" style="margin-bottom: 20px;">
            <button onclick="window.print()" class="btn">🖨️ Print Receipt</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">🔙 Back</a>
        </div>

        @php
            $user = auth()->user();
        @endphp

        <!-- Header -->
        <div class="text-center">
            <img src="{{ asset('assets/img/logo.jpeg') }}" alt="Logo" style="width: 55px; height: 55px; border-radius: 50%; margin-bottom: 8px; object-fit: cover;">
            <div class="brand-name">SALEEM TYRE HOUSE</div>
            <div class="store-info">
                {{ $user->store_address }}<br>
                <strong>Haji Naeem Ur Rehman:</strong> 0333-6881325<br>
                <strong>Bilal Naeem:</strong> 0340-1745324
            </div>
        </div>

        <!-- Order Info -->
        <div class="order-info">
            <p><strong>Invoice:</strong> {{ $order->invoice_no }}</p>
            <p><strong>Date:</strong> {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d M Y') : '' }}</p>
            <p>
                <strong>Customer:</strong> 
                {{ $order->customer_name }}
            </p>
            @if($order->customer->phone && $order->customer->phone !== '0000000000')
            <p><strong>Phone:</strong> {{ $order->customer->phone }}</p>
            @endif
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Item</th>
                    <th class="text-center" style="width: 20%;">Qty</th>
                    <th class="text-right" style="width: 30%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->details as $item)
                <tr>
                    <td>{{ $item->product->name }}<br><small>{{ Number::currency($item->unitcost, 'Rs. ') }}</small></td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ Number::currency($item->total, 'Rs. ') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <p><span>Subtotal:</span> <span>{{ Number::currency($order->sub_total, 'Rs. ') }}</span></p>
            <p><span>Tax:</span> <span>{{ Number::currency($order->vat, 'Rs. ') }}</span></p>
            <p class="bold"><span>Total:</span> <span>{{ Number::currency($order->total, 'Rs. ') }}</span></p>
            <p><span>Paid ({{ $order->payment_type }}):</span> <span>{{ Number::currency($order->pay, 'Rs. ') }}</span></p>
            @if($order->due > 0)
            <p class="bold" style="color: #d9534f;"><span>Due:</span> <span>{{ Number::currency($order->due, 'Rs. ') }}</span></p>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p style="font-size: 10px; margin-top: 10px;">Software by Awais Ejaz</p>
        </div>

    </div>

</body>
</html>
