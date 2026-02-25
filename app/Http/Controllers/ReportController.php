<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $transactions = Transaction::approved()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->with(['category', 'account'])
            ->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

        $incomeByCategory = $transactions->where('type', 'income')
            ->groupBy('category_id')
            ->map(fn($g) => ['name' => $g->first()->category->name, 'color' => $g->first()->category->color ?? '#6B7280', 'total' => $g->sum('amount')])
            ->sortByDesc('total')->values();

        $expenseByCategory = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(fn($g) => ['name' => $g->first()->category->name, 'color' => $g->first()->category->color ?? '#6B7280', 'total' => $g->sum('amount')])
            ->sortByDesc('total')->values();

        return view('reports.index', compact(
            'startDate', 'endDate', 'totalIncome', 'totalExpense',
            'incomeByCategory', 'expenseByCategory', 'transactions'
        ));
    }
}
