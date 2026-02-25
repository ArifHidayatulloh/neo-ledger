@extends('layouts.app')
@section('title', 'Anggaran')
@section('page-title', 'Anggaran')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form method="GET" action="{{ route('budgets.index') }}" class="flex gap-2">
            <input type="month" name="period" value="{{ $period }}" onchange="this.form.submit()"
                class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </form>
        @if(auth()->user()->hasPermission('budgets.manage'))
            <a href="{{ route('budgets.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Tambah Anggaran
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @forelse($budgets as $budget)
            @php
                $percentage = $budget->usage_percentage;
                $barColor = $percentage >= 100 ? 'bg-rose-500' : ($percentage >= 80 ? 'bg-amber-500' : 'bg-emerald-500');
                $textColor = $percentage >= 100 ? 'text-rose-600 dark:text-rose-400' : ($percentage >= 80 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400');
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full" style="background-color: {{ $budget->category->color ?? '#6B7280' }}"></span>
                        <h3 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $budget->category->name }}</h3>
                    </div>
                    <span class="text-xs font-medium {{ $textColor }}">{{ $percentage }}%</span>
                </div>

                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5 mb-3">
                    <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500" style="width: {{ min($percentage, 100) }}%"></div>
                </div>

                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
                    <span>Rp {{ number_format($budget->spent_amount, 0, ',', '.') }} terpakai</span>
                    <span>Rp {{ number_format($budget->limit_amount, 0, ',', '.') }} limit</span>
                </div>

                @if($budget->remaining > 0)
                    <p class="text-xs text-gray-400">Sisa: Rp {{ number_format($budget->remaining, 0, ',', '.') }}</p>
                @else
                    <p class="text-xs text-rose-500 font-medium">⚠️ Over budget: Rp {{ number_format(abs($budget->remaining), 0, ',', '.') }}</p>
                @endif

                @if(auth()->user()->hasPermission('budgets.manage'))
                    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('budgets.edit', $budget) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('budgets.destroy', $budget) }}" class="inline" onsubmit="return confirm('Hapus anggaran ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-rose-500 hover:underline">Hapus</button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-400 dark:text-gray-500">
                <p class="mb-2">Belum ada anggaran untuk periode {{ \Carbon\Carbon::parse($period . '-01')->translatedFormat('F Y') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
