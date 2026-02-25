<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\RecurringTransaction;
use Illuminate\Http\Request;

class RecurringTransactionController extends Controller
{
    public function index()
    {
        $recurring = RecurringTransaction::with(['account', 'category'])
            ->orderBy('is_active', 'desc')
            ->orderBy('next_run_date')
            ->get();

        return view('recurring.index', compact('recurring'));
    }

    public function create()
    {
        $accounts = Account::active()->orderBy('account_name')->get();
        $categories = Category::active()->orderBy('type')->orderBy('name')->get();

        return view('recurring.create', compact('accounts', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['next_run_date'] = $validated['start_date'];
        $validated['is_active'] = true;

        $recurring = RecurringTransaction::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'auditable_type' => RecurringTransaction::class,
            'auditable_id' => $recurring->id,
            'new_values' => $validated,
        ]);

        return redirect()->route('recurring.index')->with('success', 'Transaksi recurring berhasil dibuat.');
    }

    public function edit(RecurringTransaction $recurring)
    {
        $accounts = Account::active()->orderBy('account_name')->get();
        $categories = Category::active()->orderBy('type')->orderBy('name')->get();

        return view('recurring.edit', compact('recurring', 'accounts', 'categories'));
    }

    public function update(Request $request, RecurringTransaction $recurring)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'end_date' => 'nullable|date',
        ]);

        $oldValues = $recurring->only(array_keys($validated));
        $recurring->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'auditable_type' => RecurringTransaction::class,
            'auditable_id' => $recurring->id,
            'old_values' => $oldValues,
            'new_values' => $validated,
        ]);

        return redirect()->route('recurring.index')->with('success', 'Transaksi recurring berhasil diperbarui.');
    }

    public function toggleActive(RecurringTransaction $recurring)
    {
        $oldStatus = $recurring->is_active;
        $recurring->update(['is_active' => !$recurring->is_active]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'auditable_type' => RecurringTransaction::class,
            'auditable_id' => $recurring->id,
            'old_values' => ['is_active' => $oldStatus],
            'new_values' => ['is_active' => $recurring->is_active],
        ]);

        return back()->with('success', 'Status recurring berhasil diubah.');
    }

    public function destroy(RecurringTransaction $recurring)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'auditable_type' => RecurringTransaction::class,
            'auditable_id' => $recurring->id,
            'old_values' => $recurring->toArray(),
        ]);

        $recurring->delete();

        return redirect()->route('recurring.index')->with('success', 'Transaksi recurring berhasil dihapus.');
    }
}
