@extends('layouts.app')

@section('title', 'Akun / Rekening')
@section('page-title', 'Akun / Rekening')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Saldo: <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($totalBalance, 0, ',', '.') }}</span></p>
        </div>
        @if(auth()->user()->hasPermission('accounts.manage'))
            <a href="{{ route('accounts.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Tambah Akun
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($accounts as $account)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 {{ !$account->is_active ? 'opacity-50' : '' }} hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg
                            {{ $account->account_type === 'bank' ? 'bg-blue-50 dark:bg-blue-900/30' : ($account->account_type === 'e-wallet' ? 'bg-purple-50 dark:bg-purple-900/30' : 'bg-green-50 dark:bg-green-900/30') }}">
                            @if($account->account_type === 'bank') 🏦
                            @elseif($account->account_type === 'e-wallet') 💳
                            @else 💵
                            @endif
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $account->account_name }}</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ ucfirst($account->account_type) }} {{ $account->account_number ? '• ' . $account->account_number : '' }}</p>
                        </div>
                    </div>
                    @if(!$account->is_active)
                        <span class="text-xs px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-full">Nonaktif</span>
                    @endif
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Rp {{ number_format($account->current_balance, 0, ',', '.') }}</p>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $account->transactions_count }} transaksi</span>
                    @if(auth()->user()->hasPermission('accounts.manage'))
                        <div class="flex items-center gap-1">
                            <a href="{{ route('accounts.edit', $account) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('accounts.toggle', $account) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="{{ $account->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    @if($account->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    @endif
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-400 dark:text-gray-500">Belum ada akun</div>
        @endforelse
    </div>
</div>
@endsection
