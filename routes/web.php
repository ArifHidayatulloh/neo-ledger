<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecurringTransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/cashflow-data', [DashboardController::class, 'cashflowData'])->name('dashboard.cashflow-data');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Transactions ────────────────────────────────────────────
    Route::resource('transactions', TransactionController::class);
    Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
    Route::delete('/transactions/{transaction}/attachments/{attachment}', [TransactionController::class, 'deleteAttachment'])->name('transactions.delete-attachment');

    // ── Transfer ────────────────────────────────────────────────
    Route::get('/transfers/create', [TransferController::class, 'create'])->name('transfers.create');
    Route::post('/transfers', [TransferController::class, 'store'])->name('transfers.store');

    // ── Accounts ────────────────────────────────────────────────
    Route::resource('accounts', AccountController::class)->except(['show', 'destroy']);
    Route::patch('/accounts/{account}/toggle', [AccountController::class, 'toggleActive'])->name('accounts.toggle');

    // ── Categories ──────────────────────────────────────────────
    Route::resource('categories', CategoryController::class)->except(['show', 'destroy']);
    Route::patch('/categories/{category}/toggle', [CategoryController::class, 'toggleActive'])->name('categories.toggle');

    // ── Budgets ─────────────────────────────────────────────────
    Route::resource('budgets', BudgetController::class)->except(['show']);

    // ── Recurring Transactions ──────────────────────────────────
    Route::resource('recurring', RecurringTransactionController::class)->except(['show']);
    Route::patch('/recurring/{recurring}/toggle', [RecurringTransactionController::class, 'toggleActive'])->name('recurring.toggle');

    // ── Reports ─────────────────────────────────────────────────
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // ── User Management ─────────────────────────────────────────
    Route::resource('users', UserController::class)->except(['show', 'destroy']);
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');

    // ── Audit Log ───────────────────────────────────────────────
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    // ── Settings ────────────────────────────────────────────────
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // ── Export ───────────────────────────────────────────────────
    Route::get('/export/transactions', [ExportController::class, 'transactions'])->name('export.transactions');
    Route::get('/export/reports', [ExportController::class, 'reports'])->name('export.reports');
    Route::get('/export/audit-logs', [ExportController::class, 'auditLogs'])->name('export.audit-logs');

    // ── Notifications ───────────────────────────────────────────
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});

require __DIR__.'/auth.php';
