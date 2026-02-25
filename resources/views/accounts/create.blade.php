@extends('layouts.app')

@section('title', isset($account) ? 'Edit Akun' : 'Tambah Akun')
@section('page-title', isset($account) ? 'Edit Akun' : 'Tambah Akun')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form method="POST" action="{{ isset($account) ? route('accounts.update', $account) : route('accounts.store') }}">
            @csrf
            @if(isset($account)) @method('PUT') @endif

            <div class="space-y-5">
                <div>
                    <label for="account_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Akun</label>
                    <input type="text" name="account_name" id="account_name" value="{{ old('account_name', $account->account_name ?? '') }}" required
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="BCA, Mandiri, Kas Kecil...">
                    @error('account_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="account_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tipe Akun</label>
                    <select name="account_type" id="account_type" required class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Pilih Tipe</option>
                        <option value="bank" {{ old('account_type', $account->account_type ?? '') === 'bank' ? 'selected' : '' }}>Bank</option>
                        <option value="cash" {{ old('account_type', $account->account_type ?? '') === 'cash' ? 'selected' : '' }}>Kas / Cash</option>
                        <option value="e-wallet" {{ old('account_type', $account->account_type ?? '') === 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                    </select>
                    @error('account_type') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nomor Rekening <span class="text-gray-400">(opsional)</span></label>
                    <input type="text" name="account_number" id="account_number" value="{{ old('account_number', $account->account_number ?? '') }}"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="123-456-789">
                    @error('account_number') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                @if(!isset($account))
                <div>
                    <label for="current_balance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Saldo Awal</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                        <input type="number" name="current_balance" id="current_balance" value="{{ old('current_balance', 0) }}" step="0.01" min="0" required
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    @error('current_balance') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                @endif
            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('accounts.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">{{ isset($account) ? 'Simpan Perubahan' : 'Tambah Akun' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
