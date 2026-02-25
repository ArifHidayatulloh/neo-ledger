@extends('layouts.app')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Back button -->
    <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Kembali ke Daftar Transaksi
    </a>

    <!-- Main Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <!-- Header with type & amount -->
        <div class="p-6 {{ $transaction->type === 'income' ? 'bg-gradient-to-r from-emerald-500 to-green-600' : 'bg-gradient-to-r from-rose-500 to-red-600' }} text-white">
            <div class="flex items-center justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20">
                        {{ $transaction->type === 'income' ? '↗ Pemasukan' : '↙ Pengeluaran' }}
                    </span>
                    <p class="mt-2 text-3xl font-bold">
                        {{ $transaction->type === 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    @php
                        $statusClasses = [
                            'approved' => 'bg-white/20 text-white',
                            'pending'  => 'bg-amber-400 text-amber-900',
                            'rejected' => 'bg-white/30 text-white',
                        ];
                    @endphp
                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold {{ $statusClasses[$transaction->status] ?? '' }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Detail Grid -->
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-medium">Tanggal</p>
                    <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->transaction_date->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-medium">Akun</p>
                    <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->account->account_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-medium">Kategori</p>
                    <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white inline-flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $transaction->category->color ?? '#6B7280' }}"></span>
                        {{ $transaction->category->name }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-medium">Dibuat oleh</p>
                    <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->user->name }}</p>
                </div>
                @if($transaction->reference_number)
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-medium">No. Referensi</p>
                    <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white font-mono">{{ $transaction->reference_number }}</p>
                </div>
                @endif
                @if($transaction->approver)
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-medium">{{ $transaction->status === 'rejected' ? 'Ditolak oleh' : 'Disetujui oleh' }}</p>
                    <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->approver->name }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $transaction->approved_at->format('d M Y H:i') }}</p>
                </div>
                @endif
            </div>

            @if($transaction->description)
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-medium">Deskripsi</p>
                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $transaction->description }}</p>
                </div>
            @endif

            @if($transaction->rejection_note)
                <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800">
                    <p class="text-xs text-rose-600 dark:text-rose-400 uppercase tracking-wider font-medium mb-1">Alasan Penolakan</p>
                    <p class="text-sm text-rose-700 dark:text-rose-300">{{ $transaction->rejection_note }}</p>
                </div>
            @endif

            <!-- Attachments -->
            @if($transaction->attachments->count())
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-medium mb-3">Lampiran ({{ $transaction->attachments->count() }})</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($transaction->attachments as $attachment)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                                @if(str_starts_with($attachment->file_type, 'image/'))
                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="flex-shrink-0">
                                        <img src="{{ Storage::url($attachment->file_path) }}" alt="{{ $attachment->file_name }}" class="w-12 h-12 object-cover rounded-lg hover:opacity-80 transition-opacity">
                                    </a>
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="text-sm font-medium text-gray-900 dark:text-white truncate block hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                        {{ $attachment->file_name }}
                                    </a>
                                    <p class="text-xs text-gray-400">{{ number_format($attachment->file_size / 1024, 1) }} KB</p>
                                </div>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <a href="{{ Storage::url($attachment->file_path) }}" download class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors" title="Download">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                    </a>
                                    @if(auth()->user()->hasPermission('transactions.edit'))
                                    <form method="POST" action="{{ route('transactions.delete-attachment', [$transaction, $attachment]) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors" title="Hapus" onclick="return confirm('Hapus lampiran ini?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700 flex flex-wrap items-center gap-3">
            @if($transaction->isPending() && auth()->user()->hasPermission('transactions.approve'))
                <!-- Approve -->
                <form method="POST" action="{{ route('transactions.approve', $transaction) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition-colors shadow-sm" onclick="return confirm('Setujui transaksi ini?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        Setujui
                    </button>
                </form>

                <!-- Reject Modal Trigger -->
                <button type="button" x-data x-on:click="$dispatch('open-reject-modal')"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak
                </button>
            @endif

            @if(auth()->user()->hasPermission('transactions.edit') && $transaction->status !== 'rejected')
                <a href="{{ route('transactions.edit', $transaction) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 rounded-xl hover:bg-amber-100 dark:hover:bg-amber-900/50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                    Edit
                </a>
            @endif

            @if(auth()->user()->hasPermission('transactions.edit'))
                <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" class="ml-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors" onclick="return confirm('Hapus transaksi ini? Tindakan ini tidak dapat dibatalkan.')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                        Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    @if($transaction->isPending() && auth()->user()->hasPermission('transactions.approve'))
    <div x-data="{ show: false }" x-on:open-reject-modal.window="show = true" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50" @click="show = false"></div>
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 w-full max-w-md p-6 z-10">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tolak Transaksi</h3>
            <form method="POST" action="{{ route('transactions.reject', $transaction) }}">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Alasan Penolakan</label>
                    <textarea name="rejection_note" rows="3" required
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-rose-500 focus:border-transparent resize-none"
                        placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" @click="show = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition-colors">Tolak Transaksi</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
