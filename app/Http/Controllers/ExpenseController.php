<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', auth()->id())
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->paginate(25);

        $totalThisMonth = Expense::where('user_id', auth()->id())
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $totalToday = Expense::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->sum('amount');

        return view('expenses.index', compact('expenses', 'totalThisMonth', 'totalToday'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:1',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        Expense::create([
            'user_id'     => auth()->id(),
            'date'        => $request->date,
            'amount'      => $request->amount,
            'category'    => $request->category,
            'description' => $request->description,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully!');
    }

    public function destroy($id)
    {
        $expense = Expense::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted!');
    }
}
