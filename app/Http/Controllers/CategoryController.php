<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('transactions');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $categories = $query->orderBy('type')->orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:10',
            'color' => 'required|string|max:7',
        ]);

        $validated['is_active'] = true;
        $category = Category::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'auditable_type' => Category::class,
            'auditable_id' => $category->id,
            'new_values' => $validated,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:10',
            'color' => 'required|string|max:7',
        ]);

        $oldValues = $category->only(array_keys($validated));
        $category->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'auditable_type' => Category::class,
            'auditable_id' => $category->id,
            'old_values' => $oldValues,
            'new_values' => $validated,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function toggleActive(Category $category)
    {
        $oldStatus = $category->is_active;
        $category->update(['is_active' => !$category->is_active]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'auditable_type' => Category::class,
            'auditable_id' => $category->id,
            'old_values' => ['is_active' => $oldStatus],
            'new_values' => ['is_active' => $category->is_active],
        ]);

        return back()->with('success', 'Status kategori berhasil diubah.');
    }
}
