<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        $reportDate = Carbon::parse($date);

        // Sales
        $orders = Order::with('customer')
            ->whereDate('order_date', $reportDate)
            ->where('user_id', auth()->id())
            ->get();

        $totalSales      = $orders->sum('total');
        $totalCashIn     = $orders->sum('pay');
        $totalCreditGiven = $orders->sum('due');

        // Purchases
        $purchases = Purchase::with('supplier')
            ->whereDate('date', $reportDate)
            ->where('user_id', auth()->id())
            ->get();
        $totalPurchases  = $purchases->sum('total_amount');
        $totalPaidToSuppliers = $purchases->sum('paid_amount');

        // Expenses
        $expenses = Expense::where('user_id', auth()->id())
            ->whereDate('date', $reportDate)
            ->orderBy('created_at')
            ->get();
        $totalExpenses = $expenses->sum('amount');

        // Net Cash in Hand = Cash received from customers - Paid to suppliers today - Expenses today
        $netCash = $totalCashIn - $totalPaidToSuppliers - $totalExpenses;

        // Low Stock Products
        $lowStockProducts = Product::where('user_id', auth()->id())
            ->whereColumn('quantity', '<=', 'quantity_alert')
            ->where('quantity_alert', '>', 0)
            ->get();

        return view('reports.daily', compact(
            'reportDate',
            'orders',
            'totalSales',
            'totalCashIn',
            'totalCreditGiven',
            'purchases',
            'totalPurchases',
            'totalPaidToSuppliers',
            'expenses',
            'totalExpenses',
            'netCash',
            'lowStockProducts'
        ));
    }
}
