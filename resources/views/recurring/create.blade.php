@extends('layouts.app')
@section('title', isset($recurring) ? 'Edit Recurring' : 'Tambah Recurring')
@section('page-title', isset($recurring) ? 'Edit Recurring' : 'Tambah Recurring')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form method="POST" action="{{ isset($recurring) ? route('recurring.update', $recurring) : route('recurring.store') }}">
            @csrf
            @if(isset($recurring)) @method('PUT') @endif
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe</label>
                    <div class="flex gap-3">
                        <label class="flex-1 relative">
                            <input type="radio" name="type" value="income" class="peer sr-only" {{ old('type', $recurring->type ?? '') === 'income' ? 'checked' : '' }} required>
                            <div class="p-3 text-center rounded-xl border-2 border-gray-200 dark:border-gray-600 cursor-pointer transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 text-gray-500"><span class="text-sm font-medium">Pemasukan</span></div>
                        </label>
                        <label class="flex-1 relative">
                            <input type="radio" name="type" value="expense" class="peer sr-only" {{ old('type', $recurring->type ?? '') === 'expense' ? 'checked' : '' }}>
                            <div class="p-3 text-center rounded-xl border-2 border-gray-200 dark:border-gray-600 cursor-pointer transition-all peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/20 text-gray-500"><span class="text-sm font-medium">Pengeluaran</span></div>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nominal</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                        <input type="number" name="amount" id="amount" value="{{ old('amount', $recurring->amount ?? '') }}" step="0.01" min="0.01" required class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>
                <div>
                    <label for="account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Akun</label>
                    <select name="account_id" id="account_id" required class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Pilih Akun</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ old('account_id', $recurring->account_id ?? '') == $acc->id ? 'selected' : '' }}>{{ $acc->account_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kategori</label>
                    <select name="category_id" id="category_id" required class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" data-type="{{ $cat->type }}" {{ old('category_id', $recurring->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="frequency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Frekuensi</label>
                    <select name="frequency" id="frequency" required class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="daily" {{ old('frequency', $recurring->frequency ?? '') === 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ old('frequency', $recurring->frequency ?? '') === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ old('frequency', $recurring->frequency ?? '') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        <option value="yearly" {{ old('frequency', $recurring->frequency ?? '') === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>
                @if(!isset($recurring))
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                @endif
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Berakhir <span class="text-gray-400">(opsional)</span></label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', isset($recurring) && $recurring->end_date ? $recurring->end_date->format('Y-m-d') : '') }}" class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
                    <textarea name="description" id="description" rows="2" class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none">{{ old('description', $recurring->description ?? '') }}</textarea>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('recurring.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">{{ isset($recurring) ? 'Simpan' : 'Tambah' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
