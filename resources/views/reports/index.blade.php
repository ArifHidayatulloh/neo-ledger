@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Dari</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Sampai</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-sm font-medium rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">Tampilkan</button>
            <div class="ml-auto">
                <x-export-button route="export.reports" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Pemasukan</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Pengeluaran</p>
            <p class="text-2xl font-bold text-rose-600 dark:text-rose-400 mt-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Selisih (Net)</p>
            <p class="text-2xl font-bold {{ $totalIncome - $totalExpense >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} mt-1">
                Rp {{ number_format(abs($totalIncome - $totalExpense), 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Income by Category -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Pemasukan per Kategori</h3>
            @forelse($incomeByCategory as $cat)
                @php $pct = $totalIncome > 0 ? round($cat['total'] / $totalIncome * 100, 1) : 0; @endphp
                <div class="mb-3">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-600 dark:text-gray-400">{{ $cat['name'] }}</span>
                        <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($cat['total'], 0, ',', '.') }} ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full" style="width: {{ $pct }}%; background-color: {{ $cat['color'] }}"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400">Tidak ada data</p>
            @endforelse
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Pengeluaran per Kategori</h3>
            @forelse($expenseByCategory as $cat)
                @php $pct = $totalExpense > 0 ? round($cat['total'] / $totalExpense * 100, 1) : 0; @endphp
                <div class="mb-3">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-600 dark:text-gray-400">{{ $cat['name'] }}</span>
                        <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($cat['total'], 0, ',', '.') }} ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full" style="width: {{ $pct }}%; background-color: {{ $cat['color'] }}"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400">Tidak ada data</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
