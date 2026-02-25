<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ApprovalSetting;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionAttachment;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['account', 'category', 'user', 'approver']);

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('date_from')) {
            $query->where('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('transaction_date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('reference_number', 'like', '%' . $request->search . '%');
            });
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(15)
                              ->withQueryString();

        $accounts = Account::active()->orderBy('account_name')->get();
        $categories = Category::active()->orderBy('name')->get();

        return view('transactions.index', compact('transactions', 'accounts', 'categories'));
    }

    public function create()
    {
        $accounts = Account::active()->orderBy('account_name')->get();
        $categories = Category::active()->orderBy('type')->orderBy('name')->get();

        return view('transactions.create', compact('accounts', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'reference_number' => 'nullable|string|max:100',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $validated['user_id'] = auth()->id();

        // Check approval threshold
        $threshold = ApprovalSetting::getThreshold($validated['type']);
        if ($threshold && $validated['amount'] >= $threshold) {
            $validated['status'] = 'pending';
        } else {
            $validated['status'] = 'approved';
            $validated['approved_by'] = auth()->id();
            $validated['approved_at'] = now();
        }

        $createdTransaction = null;

        DB::transaction(function () use ($request, $validated, &$createdTransaction) {
            $createdTransaction = Transaction::create(collect($validated)->except('attachments')->toArray());

            // Upload attachments
            $this->handleAttachments($request, $createdTransaction);

            // Update account balance if auto-approved
            if ($createdTransaction->status === 'approved') {
                $this->updateAccountBalance($createdTransaction);
            }

            // Audit log
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'auditable_type' => Transaction::class,
                'auditable_id' => $createdTransaction->id,
                'new_values' => $validated,
            ]);
        });

        // Notify approvers if pending
        if ($createdTransaction && $createdTransaction->status === 'pending') {
            NotificationService::transactionPending(
                $createdTransaction->id,
                $createdTransaction->description ?? '',
                number_format($createdTransaction->amount, 0, ',', '.'),
                auth()->user()->name
            );
        }

        $message = $validated['status'] === 'pending'
            ? 'Transaksi berhasil dibuat dan menunggu persetujuan.'
            : 'Transaksi berhasil dibuat dan disetujui.';

        return redirect()->route('transactions.index')->with('success', $message);
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['account', 'category', 'user', 'approver', 'attachments', 'relatedTransaction']);

        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        if ($transaction->status === 'rejected') {
            return redirect()->route('transactions.index')
                ->with('error', 'Transaksi yang ditolak tidak dapat diedit.');
        }

        $transaction->load('attachments');
        $accounts = Account::active()->orderBy('account_name')->get();
        $categories = Category::active()->orderBy('type')->orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'accounts', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->status === 'rejected') {
            return redirect()->route('transactions.index')
                ->with('error', 'Transaksi yang ditolak tidak dapat diedit.');
        }

        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'reference_number' => 'nullable|string|max:100',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $oldValues = $transaction->only(array_keys(collect($validated)->except('attachments')->toArray()));

        DB::transaction(function () use ($request, $transaction, $validated, $oldValues) {
            // If was approved, reverse the old balance first
            if ($transaction->status === 'approved') {
                $this->reverseAccountBalance($transaction);
            }

            // Re-check approval threshold
            $threshold = ApprovalSetting::getThreshold($validated['type']);
            if ($threshold && $validated['amount'] >= $threshold) {
                $validated['status'] = 'pending';
                $validated['approved_by'] = null;
                $validated['approved_at'] = null;
            } else {
                $validated['status'] = 'approved';
                $validated['approved_by'] = auth()->id();
                $validated['approved_at'] = now();
            }

            $transaction->update(collect($validated)->except('attachments')->toArray());

            // Upload new attachments
            $this->handleAttachments($request, $transaction);

            // Update balance if auto-approved
            if ($transaction->status === 'approved') {
                $this->updateAccountBalance($transaction);
            }

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'update',
                'auditable_type' => Transaction::class,
                'auditable_id' => $transaction->id,
                'old_values' => $oldValues,
                'new_values' => $validated,
            ]);
        });

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            // Reverse balance if was approved
            if ($transaction->status === 'approved') {
                $this->reverseAccountBalance($transaction);
            }

            // Delete attachment files from storage
            foreach ($transaction->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete',
                'auditable_type' => Transaction::class,
                'auditable_id' => $transaction->id,
                'old_values' => $transaction->toArray(),
            ]);

            $transaction->delete(); // cascade deletes attachments from DB
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    public function deleteAttachment(Transaction $transaction, TransactionAttachment $attachment)
    {
        if ($attachment->transaction_id !== $transaction->id) {
            abort(403);
        }

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return back()->with('success', 'Lampiran berhasil dihapus.');
    }

    public function approve(Transaction $transaction)
    {
        if (!$transaction->isPending()) {
            return back()->with('error', 'Transaksi ini tidak dalam status pending.');
        }

        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $this->updateAccountBalance($transaction);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'approve',
                'auditable_type' => Transaction::class,
                'auditable_id' => $transaction->id,
                'new_values' => ['status' => 'approved'],
            ]);
        });

        // Notify the transaction creator
        NotificationService::transactionApproved(
            $transaction->id,
            $transaction->user_id,
            auth()->user()->name,
            number_format($transaction->amount, 0, ',', '.')
        );

        return back()->with('success', 'Transaksi berhasil disetujui.');
    }

    public function reject(Request $request, Transaction $transaction)
    {
        if (!$transaction->isPending()) {
            return back()->with('error', 'Transaksi ini tidak dalam status pending.');
        }

        $request->validate([
            'rejection_note' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($request, $transaction) {
            $transaction->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_note' => $request->rejection_note,
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'reject',
                'auditable_type' => Transaction::class,
                'auditable_id' => $transaction->id,
                'new_values' => ['status' => 'rejected', 'rejection_note' => $request->rejection_note],
            ]);
        });

        // Notify the transaction creator
        NotificationService::transactionRejected(
            $transaction->id,
            $transaction->user_id,
            auth()->user()->name,
            number_format($transaction->amount, 0, ',', '.'),
            $request->rejection_note
        );

        return back()->with('success', 'Transaksi berhasil ditolak.');
    }

    // ── Private Helpers ──

    private function handleAttachments(Request $request, Transaction $transaction): void
    {
        if (!$request->hasFile('attachments')) return;

        foreach ($request->file('attachments') as $file) {
            $path = $file->store('attachments/transactions/' . $transaction->id, 'public');

            TransactionAttachment::create([
                'transaction_id' => $transaction->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'created_at' => now(),
            ]);
        }
    }

    private function updateAccountBalance(Transaction $transaction): void
    {
        $account = Account::find($transaction->account_id);
        if (!$account) return;

        if ($transaction->type === 'income') {
            $account->increment('current_balance', $transaction->amount);
        } else {
            $account->decrement('current_balance', $transaction->amount);
        }
    }

    private function reverseAccountBalance(Transaction $transaction): void
    {
        $account = Account::find($transaction->account_id);
        if (!$account) return;

        if ($transaction->type === 'income') {
            $account->decrement('current_balance', $transaction->amount);
        } else {
            $account->increment('current_balance', $transaction->amount);
        }
    }
}
