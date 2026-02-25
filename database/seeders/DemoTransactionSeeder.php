<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@neoledger.com')->first();
        $accounts = Account::all();
        $incomeCategories = Category::where('type', 'income')->pluck('id')->toArray();
        $expenseCategories = Category::where('type', 'expense')->pluck('id')->toArray();

        $now = Carbon::now();

        // Generate transactions for the last 6 months
        for ($m = 5; $m >= 0; $m--) {
            $monthDate = $now->copy()->subMonths($m);
            $year = $monthDate->year;
            $month = $monthDate->month;
            $daysInMonth = $monthDate->daysInMonth;

            // 3-5 income transactions per month
            $incomeCount = rand(3, 5);
            for ($i = 0; $i < $incomeCount; $i++) {
                $day = rand(1, $daysInMonth);
                $amount = rand(10, 80) * 1000000; // 10jt - 80jt

                Transaction::create([
                    'user_id' => $admin->id,
                    'account_id' => $accounts->random()->id,
                    'category_id' => $incomeCategories[array_rand($incomeCategories)],
                    'type' => 'income',
                    'amount' => $amount,
                    'transaction_date' => Carbon::create($year, $month, $day),
                    'description' => 'Income transaction #' . ($i + 1) . ' bulan ' . $monthDate->format('M Y'),
                    'status' => 'approved',
                    'approved_by' => $admin->id,
                    'approved_at' => Carbon::create($year, $month, $day),
                ]);
            }

            // 5-8 expense transactions per month
            $expenseCount = rand(5, 8);
            for ($i = 0; $i < $expenseCount; $i++) {
                $day = rand(1, $daysInMonth);
                $amount = rand(1, 25) * 1000000; // 1jt - 25jt

                Transaction::create([
                    'user_id' => $admin->id,
                    'account_id' => $accounts->random()->id,
                    'category_id' => $expenseCategories[array_rand($expenseCategories)],
                    'type' => 'expense',
                    'amount' => $amount,
                    'transaction_date' => Carbon::create($year, $month, $day),
                    'description' => 'Expense transaction #' . ($i + 1) . ' bulan ' . $monthDate->format('M Y'),
                    'status' => 'approved',
                    'approved_by' => $admin->id,
                    'approved_at' => Carbon::create($year, $month, $day),
                ]);
            }
        }

        // Add a few pending transactions for current month
        for ($i = 0; $i < 3; $i++) {
            Transaction::create([
                'user_id' => $admin->id,
                'account_id' => $accounts->random()->id,
                'category_id' => $expenseCategories[array_rand($expenseCategories)],
                'type' => 'expense',
                'amount' => rand(5, 15) * 1000000,
                'transaction_date' => $now->copy()->subDays(rand(0, 5)),
                'description' => 'Pending expense #' . ($i + 1) . ' - menunggu approval',
                'status' => 'pending',
            ]);
        }

        // Create budgets for current month
        $currentPeriod = $now->format('Y-m');
        $expenseCats = Category::where('type', 'expense')->get();

        foreach ($expenseCats as $cat) {
            $spent = Transaction::where('category_id', $cat->id)
                ->where('type', 'expense')
                ->where('status', 'approved')
                ->whereYear('transaction_date', $now->year)
                ->whereMonth('transaction_date', $now->month)
                ->sum('amount');

            Budget::create([
                'category_id' => $cat->id,
                'limit_amount' => max($spent * 1.3, 10000000), // 30% buffer or min 10jt
                'spent_amount' => $spent,
                'period' => $currentPeriod,
            ]);
        }
    }
}
