<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function create()
    {
        $accounts = Account::active()->orderBy('account_name')->get();

        return view('transactions.transfer', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id|different:from_account_id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ]);

        // Get or create transfer category
        $category = Category::firstOrCreate(
            ['name' => 'Transfer', 'type' => 'expense'],
            ['icon' => '🔄', 'color' => '#6366F1', 'is_active' => true]
        );
        $incomeCategory = Category::firstOrCreate(
            ['name' => 'Transfer Masuk', 'type' => 'income'],
            ['icon' => '🔄', 'color' => '#6366F1', 'is_active' => true]
        );

        DB::transaction(function () use ($validated, $category, $incomeCategory) {
            // Expense from source account
            $expenseTx = Transaction::create([
                'user_id' => auth()->id(),
                'account_id' => $validated['from_account_id'],
                'category_id' => $category->id,
                'type' => 'expense',
                'amount' => $validated['amount'],
                'transaction_date' => $validated['transaction_date'],
                'description' => $validated['description'] ?: 'Transfer ke ' . Account::find($validated['to_account_id'])->account_name,
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Income to destination account
            $incomeTx = Transaction::create([
                'user_id' => auth()->id(),
                'account_id' => $validated['to_account_id'],
                'category_id' => $incomeCategory->id,
                'type' => 'income',
                'amount' => $validated['amount'],
                'transaction_date' => $validated['transaction_date'],
                'description' => $validated['description'] ?: 'Transfer dari ' . Account::find($validated['from_account_id'])->account_name,
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'related_transaction_id' => $expenseTx->id,
            ]);

            // Link back
            $expenseTx->update(['related_transaction_id' => $incomeTx->id]);

            // Update balances
            Account::find($validated['from_account_id'])->decrement('current_balance', $validated['amount']);
            Account::find($validated['to_account_id'])->increment('current_balance', $validated['amount']);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'transfer',
                'auditable_type' => Transaction::class,
                'auditable_id' => $expenseTx->id,
                'new_values' => $validated,
            ]);
        });

        return redirect()->route('transactions.index')->with('success', 'Transfer berhasil dilakukan.');
    }
}
