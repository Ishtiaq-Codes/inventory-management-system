<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::where("user_id", auth()->id())->count();
        $products = Product::where("user_id", auth()->id())->count();

        $purchases = Purchase::where("user_id", auth()->id())->count();
        $todayPurchases = Purchase::whereDate('date', today()->format('Y-m-d'))->count();
        $todayProducts = Product::whereDate('created_at', today()->format('Y-m-d'))->count();
        $todayQuotations = Quotation::whereDate('created_at', today()->format('Y-m-d'))->count();
        $todayOrders = Order::whereDate('order_date', today()->format('Y-m-d'))->count();

        $categories = Category::where("user_id", auth()->id())->count();
        $quotations = Quotation::where("user_id", auth()->id())->count();

        // All Time Financials
        $totalStockValue = Product::selectRaw('SUM(buying_price * quantity) as stock_cost')->value('stock_cost') ?? 0;
        $totalReceivables = Order::sum('due');
        $totalPayables = Purchase::selectRaw('SUM(total_amount - paid_amount) as total_payable')->value('total_payable') ?? 0;
        $totalSales = Order::sum('total');
        $totalPurchases = Purchase::sum('total_amount');

        // Total Business Value: Stock + Receivables + Cash - Payables
        $totalBusinessValue = $totalStockValue + $totalSales - $totalPurchases;

        // Today's Activity
        $todaySalesAmount    = Order::whereDate('order_date', today()->format('Y-m-d'))->sum('total');
        $todayReceivedAmount = Order::whereDate('order_date', today()->format('Y-m-d'))->sum('pay');
        $todayCreditAmount   = Order::whereDate('order_date', today()->format('Y-m-d'))->sum('due');
        $todayPurchasesAmount = Purchase::whereDate('date', today()->format('Y-m-d'))->sum('total_amount');
        $todayExpensesAmount  = Expense::where('user_id', auth()->id())
                                    ->whereDate('date', today()->format('Y-m-d'))
                                    ->sum('amount');

        // Low Stock Alert
        $lowStockCount = Product::where('user_id', auth()->id())
            ->whereColumn('quantity', '<=', 'quantity_alert')
            ->where('quantity_alert', '>', 0)
            ->count();

        return view('dashboard', [
            'products'             => $products,
            'orders'               => $orders,
            'purchases'            => $purchases,
            'todayPurchases'       => $todayPurchases,
            'todayProducts'        => $todayProducts,
            'todayQuotations'      => $todayQuotations,
            'todayOrders'          => $todayOrders,
            'categories'           => $categories,
            'quotations'           => $quotations,
            'totalStockValue'      => $totalStockValue,
            'totalReceivables'     => $totalReceivables,
            'totalPayables'        => $totalPayables,
            'totalSales'           => $totalSales,
            'totalPurchases'       => $totalPurchases,
            'totalBusinessValue'   => $totalBusinessValue,
            'todaySalesAmount'     => $todaySalesAmount,
            'todayReceivedAmount'  => $todayReceivedAmount,
            'todayCreditAmount'    => $todayCreditAmount,
            'todayPurchasesAmount' => $todayPurchasesAmount,
            'todayExpensesAmount'  => $todayExpensesAmount,
            'lowStockCount'        => $lowStockCount,
        ]);
    }
}
