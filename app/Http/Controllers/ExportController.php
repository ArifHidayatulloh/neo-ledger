<?php

namespace App\Http\Controllers;

use App\Exports\AuditLogsExport;
use App\Exports\ReportsExport;
use App\Exports\TransactionsExport;
use App\Models\AuditLog;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function transactions(Request $request)
    {
        $filters = $request->only(['type', 'status', 'account_id', 'category_id', 'date_from', 'date_to']);
        $filename = 'transaksi_' . now()->format('Y-m-d_His');

        return match ($request->get('format', 'xlsx')) {
            'csv' => Excel::download(new TransactionsExport($filters), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV),
            'pdf' => $this->transactionsPdf($filters, $filename),
            default => Excel::download(new TransactionsExport($filters), $filename . '.xlsx'),
        };
    }

    public function reports(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date']);
        $filename = 'laporan_' . ($filters['start_date'] ?? now()->format('Y-m-d')) . '_' . ($filters['end_date'] ?? now()->format('Y-m-d'));

        return match ($request->get('format', 'xlsx')) {
            'csv' => Excel::download(new ReportsExport($filters), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV),
            'pdf' => $this->reportsPdf($filters, $filename),
            default => Excel::download(new ReportsExport($filters), $filename . '.xlsx'),
        };
    }

    public function auditLogs(Request $request)
    {
        $filters = $request->only(['action', 'date_from', 'date_to']);
        $filename = 'audit_log_' . now()->format('Y-m-d_His');

        return match ($request->get('format', 'xlsx')) {
            'csv' => Excel::download(new AuditLogsExport($filters), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV),
            'pdf' => $this->auditLogsPdf($filters, $filename),
            default => Excel::download(new AuditLogsExport($filters), $filename . '.xlsx'),
        };
    }

    // ── PDF Helpers ─────────────────────────────────────────────

    private function transactionsPdf(array $filters, string $filename)
    {
        $query = Transaction::with(['account', 'category', 'user'])
            ->orderBy('transaction_date', 'desc');

        foreach (['type', 'status', 'account_id', 'category_id'] as $key) {
            if (!empty($filters[$key])) $query->where($key, $filters[$key]);
        }
        if (!empty($filters['date_from'])) $query->where('transaction_date', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->where('transaction_date', '<=', $filters['date_to']);

        $pdf = Pdf::loadView('exports.transactions-pdf', [
            'transactions' => $query->get(),
            'filters' => $filters,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename . '.pdf');
    }

    private function reportsPdf(array $filters, string $filename)
    {
        $startDate = $filters['start_date'] ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $filters['end_date'] ?? now()->endOfMonth()->format('Y-m-d');

        $transactions = Transaction::approved()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->with(['category', 'account'])->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

        $incomeByCategory = $transactions->where('type', 'income')
            ->groupBy('category_id')
            ->map(fn($g) => ['name' => $g->first()->category->name, 'total' => $g->sum('amount')])
            ->sortByDesc('total')->values();

        $expenseByCategory = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(fn($g) => ['name' => $g->first()->category->name, 'total' => $g->sum('amount')])
            ->sortByDesc('total')->values();

        $pdf = Pdf::loadView('exports.reports-pdf', compact(
            'startDate', 'endDate', 'totalIncome', 'totalExpense',
            'incomeByCategory', 'expenseByCategory', 'transactions'
        ))->setPaper('a4', 'portrait');

        return $pdf->download($filename . '.pdf');
    }

    private function auditLogsPdf(array $filters, string $filename)
    {
        $query = AuditLog::with('user')->latest();

        if (!empty($filters['action'])) $query->where('action', $filters['action']);
        if (!empty($filters['date_from'])) $query->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('created_at', '<=', $filters['date_to']);

        $pdf = Pdf::loadView('exports.audit-logs-pdf', [
            'logs' => $query->get(),
            'filters' => $filters,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename . '.pdf');
    }
}
