<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;

        // Stat cards
        $totalBalance = Account::active()->sum('current_balance');

        $monthIncome = Transaction::approved()
            ->income()
            ->forMonth($year, $month)
            ->sum('amount');

        $monthExpense = Transaction::approved()
            ->expense()
            ->forMonth($year, $month)
            ->sum('amount');

        $netCashflow = $monthIncome - $monthExpense;

        // Latest transactions
        $latestTransactions = Transaction::with(['account', 'category', 'user'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Pending count
        $pendingCount = Transaction::pending()->count();

        // Budgets near limit (top 3)
        $budgetsNearLimit = Budget::with('category')
            ->where('period', $now->format('Y-m'))
            ->get()
            ->sortByDesc('usage_percentage')
            ->take(3);

        // Expense by category (current month)
        $expenseByCategory = Transaction::approved()
            ->expense()
            ->forMonth($year, $month)
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($group) {
                $category = $group->first()->category;
                return [
                    'name' => $category->name,
                    'color' => $category->color ?? '#6B7280',
                    'total' => $group->sum('amount'),
                ];
            })
            ->sortByDesc('total')
            ->values();

        return view('dashboard.index', compact(
            'totalBalance',
            'monthIncome',
            'monthExpense',
            'netCashflow',
            'latestTransactions',
            'pendingCount',
            'budgetsNearLimit',
            'expenseByCategory'
        ));
    }

    public function cashflowData(): JsonResponse
    {
        $now = Carbon::now();
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $labels[] = $date->translatedFormat('M Y');

            $incomeData[] = Transaction::approved()
                ->income()
                ->forMonth($date->year, $date->month)
                ->sum('amount');

            $expenseData[] = Transaction::approved()
                ->expense()
                ->forMonth($date->year, $date->month)
                ->sum('amount');
        }

        return response()->json([
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData,
        ]);
    }
}
