@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <!-- Total Balance -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-md transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Saldo</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalBalance, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Semua akun aktif</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-indigo-900/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-indigo-100 dark:bg-indigo-900/20 rounded-full opacity-50"></div>
        </div>

        <!-- Income -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-md transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pemasukan Bulan Ini</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($monthIncome, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 dark:shadow-emerald-900/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-emerald-100 dark:bg-emerald-900/20 rounded-full opacity-50"></div>
        </div>

        <!-- Expense -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-md transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengeluaran Bulan Ini</p>
                    <p class="mt-2 text-2xl font-bold text-rose-600 dark:text-rose-400">Rp {{ number_format($monthExpense, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-rose-400 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-rose-200 dark:shadow-rose-900/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.51l-5.511-3.181"/></svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-rose-100 dark:bg-rose-900/20 rounded-full opacity-50"></div>
        </div>

        <!-- Net Cashflow -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-md transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Net Cashflow</p>
                    <p class="mt-2 text-2xl font-bold {{ $netCashflow >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        {{ $netCashflow >= 0 ? '+' : '-' }}Rp {{ number_format(abs($netCashflow), 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Income - Expense</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br {{ $netCashflow >= 0 ? 'from-blue-400 to-cyan-600 shadow-blue-200 dark:shadow-blue-900/50' : 'from-amber-400 to-orange-600 shadow-amber-200 dark:shadow-amber-900/50' }} rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 {{ $netCashflow >= 0 ? 'bg-blue-100 dark:bg-blue-900/20' : 'bg-amber-100 dark:bg-amber-900/20' }} rounded-full opacity-50"></div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
        <!-- Cashflow Trend Chart (spans 2 cols) -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Arus Kas 6 Bulan Terakhir</h3>
                <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-full">Trend</span>
            </div>
            <div class="relative" style="height: 280px;">
                <canvas id="cashflowChart"></canvas>
            </div>
        </div>

        <!-- Expense Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Pengeluaran per Kategori</h3>
                <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-full">Bulan Ini</span>
            </div>
            @if($expenseByCategory->isNotEmpty())
                <div class="relative mx-auto" style="height: 200px;">
                    <canvas id="expenseChart"></canvas>
                </div>
                <div class="mt-4 space-y-2 max-h-32 overflow-y-auto">
                    @foreach($expenseByCategory as $cat)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $cat['color'] }}"></span>
                                <span class="text-gray-600 dark:text-gray-400 truncate">{{ $cat['name'] }}</span>
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white whitespace-nowrap">Rp {{ number_format($cat['total'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-center h-48 text-gray-400 dark:text-gray-500">
                    <p class="text-sm">Belum ada pengeluaran bulan ini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
        <!-- Latest Transactions (spans 2 cols) -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between p-5 pb-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Transaksi Terakhir</h3>
                @if($pendingCount > 0)
                    <span class="text-xs font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2.5 py-1 rounded-full">
                        {{ $pendingCount }} pending
                    </span>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Akun</th>
                            <th class="text-right py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nominal</th>
                            <th class="text-center py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @forelse($latestTransactions as $tx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-3 px-5 text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $tx->transaction_date->format('d M Y') }}</td>
                                <td class="py-3 px-5 text-gray-900 dark:text-white font-medium max-w-[200px] truncate">{{ $tx->description ?: '-' }}</td>
                                <td class="py-3 px-5">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full" style="background-color: {{ $tx->category->color ?? '#6B7280' }}"></span>
                                        <span class="text-gray-600 dark:text-gray-400">{{ $tx->category->name }}</span>
                                    </span>
                                </td>
                                <td class="py-3 px-5 text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $tx->account->account_name }}</td>
                                <td class="py-3 px-5 text-right font-semibold whitespace-nowrap {{ $tx->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-5 text-center">
                                    @php
                                        $statusClasses = [
                                            'approved' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                            'pending' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                            'rejected' => 'bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$tx->status] ?? '' }}">
                                        {{ ucfirst($tx->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400 dark:text-gray-500">Belum ada transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Budget Status -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Status Anggaran</h3>
                <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-full">{{ now()->format('M Y') }}</span>
            </div>
            <div class="space-y-4">
                @forelse($budgetsNearLimit as $budget)
                    @php
                        $pct = $budget->usage_percentage;
                        if ($pct >= 100) $barColor = 'bg-rose-500';
                        elseif ($pct >= 80) $barColor = 'bg-amber-500';
                        else $barColor = 'bg-emerald-500';
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ $budget->category->name }}</span>
                            <span class="text-xs font-semibold {{ $pct >= 100 ? 'text-rose-600 dark:text-rose-400' : ($pct >= 80 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                                {{ $pct }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500" style="width: {{ min($pct, 100) }}%"></div>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-[11px] text-gray-400 dark:text-gray-500">Rp {{ number_format($budget->spent_amount, 0, ',', '.') }}</span>
                            <span class="text-[11px] text-gray-400 dark:text-gray-500">Rp {{ number_format($budget->limit_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-32 text-gray-400 dark:text-gray-500">
                        <p class="text-sm">Belum ada anggaran</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
    const textColor = isDark ? '#9CA3AF' : '#6B7280';

    // Cashflow Trend Chart
    fetch('{{ route("dashboard.cashflow-data") }}')
        .then(res => res.json())
        .then(data => {
            new Chart(document.getElementById('cashflowChart'), {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: data.income,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: '#10B981',
                        },
                        {
                            label: 'Pengeluaran',
                            data: data.expense,
                            borderColor: '#F43F5E',
                            backgroundColor: 'rgba(244, 63, 94, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: '#F43F5E',
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { color: textColor, usePointStyle: true, pointStyle: 'circle', padding: 16, font: { size: 12 } },
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1F2937' : '#FFFFFF',
                            titleColor: isDark ? '#F9FAFB' : '#111827',
                            bodyColor: isDark ? '#D1D5DB' : '#4B5563',
                            borderColor: isDark ? '#374151' : '#E5E7EB',
                            borderWidth: 1,
                            padding: 12,
                            callbacks: {
                                label: ctx => ctx.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw),
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 11 } },
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: {
                                color: textColor,
                                font: { size: 11 },
                                callback: v => 'Rp ' + (v / 1000000).toFixed(0) + ' jt',
                            },
                        },
                    },
                },
            });
        });

    // Expense Breakdown Doughnut
    @if($expenseByCategory->isNotEmpty())
    new Chart(document.getElementById('expenseChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($expenseByCategory->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($expenseByCategory->pluck('total')) !!},
                backgroundColor: {!! json_encode($expenseByCategory->pluck('color')) !!},
                borderWidth: 0,
                hoverOffset: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? '#1F2937' : '#FFFFFF',
                    titleColor: isDark ? '#F9FAFB' : '#111827',
                    bodyColor: isDark ? '#D1D5DB' : '#4B5563',
                    borderColor: isDark ? '#374151' : '#E5E7EB',
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label: ctx => ctx.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw),
                    },
                },
            },
        },
    });
    @endif
});
</script>
@endpush
