<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::withCount('transactions')->orderBy('account_name')->get();
        $totalBalance = $accounts->where('is_active', true)->sum('current_balance');

        return view('accounts.index', compact('accounts', 'totalBalance'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255|unique:accounts,account_name',
            'account_type' => 'required|in:bank,cash,e-wallet',
            'account_number' => 'nullable|string|max:100',
            'current_balance' => 'required|numeric|min:0',
        ]);

        $validated['is_active'] = true;
        $account = Account::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'auditable_type' => Account::class,
            'auditable_id' => $account->id,
            'new_values' => $validated,
        ]);

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(Account $account)
    {
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255|unique:accounts,account_name,' . $account->id,
            'account_type' => 'required|in:bank,cash,e-wallet',
            'account_number' => 'nullable|string|max:100',
        ]);

        $oldValues = $account->only(array_keys($validated));
        $account->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'auditable_type' => Account::class,
            'auditable_id' => $account->id,
            'old_values' => $oldValues,
            'new_values' => $validated,
        ]);

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function toggleActive(Account $account)
    {
        $oldStatus = $account->is_active;
        $account->update(['is_active' => !$account->is_active]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'auditable_type' => Account::class,
            'auditable_id' => $account->id,
            'old_values' => ['is_active' => $oldStatus],
            'new_values' => ['is_active' => $account->is_active],
        ]);

        return back()->with('success', 'Status akun berhasil diubah.');
    }
}
