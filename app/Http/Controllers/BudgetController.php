<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', now()->format('Y-m'));

        $budgets = Budget::with('category')
            ->where('period', $period)
            ->get()
            ->sortByDesc('usage_percentage');

        $categories = Category::active()->expense()->orderBy('name')->get();

        return view('budgets.index', compact('budgets', 'categories', 'period'));
    }

    public function create()
    {
        $categories = Category::active()->expense()->orderBy('name')->get();

        return view('budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'limit_amount' => 'required|numeric|min:1',
            'period' => 'required|date_format:Y-m',
        ]);

        $validated['spent_amount'] = 0;

        // Check if budget already exists for this category+period
        $exists = Budget::where('category_id', $validated['category_id'])
            ->where('period', $validated['period'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Budget untuk kategori dan periode ini sudah ada.');
        }

        $budget = Budget::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'auditable_type' => Budget::class,
            'auditable_id' => $budget->id,
            'new_values' => $validated,
        ]);

        return redirect()->route('budgets.index', ['period' => $validated['period']])
            ->with('success', 'Anggaran berhasil ditambahkan.');
    }

    public function edit(Budget $budget)
    {
        $categories = Category::active()->expense()->orderBy('name')->get();

        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'limit_amount' => 'required|numeric|min:1',
        ]);

        $oldValues = $budget->only(['limit_amount']);
        $budget->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'auditable_type' => Budget::class,
            'auditable_id' => $budget->id,
            'old_values' => $oldValues,
            'new_values' => $validated,
        ]);

        return redirect()->route('budgets.index', ['period' => $budget->period])
            ->with('success', 'Anggaran berhasil diperbarui.');
    }

    public function destroy(Budget $budget)
    {
        $period = $budget->period;

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'auditable_type' => Budget::class,
            'auditable_id' => $budget->id,
            'old_values' => $budget->toArray(),
        ]);

        $budget->delete();

        return redirect()->route('budgets.index', ['period' => $period])
            ->with('success', 'Anggaran berhasil dihapus.');
    }
}
