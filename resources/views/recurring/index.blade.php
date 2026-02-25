@extends('layouts.app')
@section('title', 'Recurring Transactions')
@section('page-title', 'Recurring Transactions')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola transaksi berulang otomatis</p>
        @if(auth()->user()->hasPermission('recurring.create'))
            <a href="{{ route('recurring.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Tambah Recurring
            </a>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Deskripsi</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Kategori</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Frekuensi</th>
                        <th class="text-right py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Nominal</th>
                        <th class="text-center py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Next Run</th>
                        <th class="text-center py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                        <th class="text-center py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($recurring as $rec)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ !$rec->is_active ? 'opacity-50' : '' }}">
                            <td class="py-3 px-5">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $rec->description ?: 'Tanpa deskripsi' }}</p>
                                <p class="text-xs text-gray-400">{{ $rec->account->account_name }}</p>
                            </td>
                            <td class="py-3 px-5 text-gray-600 dark:text-gray-400">{{ $rec->category->name }}</td>
                            <td class="py-3 px-5">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400">{{ ucfirst($rec->frequency) }}</span>
                            </td>
                            <td class="py-3 px-5 text-right font-semibold {{ $rec->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                Rp {{ number_format($rec->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-5 text-center text-gray-600 dark:text-gray-400 text-xs">
                                {{ $rec->next_run_date ? $rec->next_run_date->format('d M Y') : '-' }}
                            </td>
                            <td class="py-3 px-5 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $rec->is_active ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                                    {{ $rec->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3 px-5 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('recurring.edit', $rec) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('recurring.toggle', $rec) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-amber-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9"/></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('recurring.destroy', $rec) }}" class="inline" onsubmit="return confirm('Hapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">Belum ada recurring transaction</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
