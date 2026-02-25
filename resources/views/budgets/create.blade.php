@extends('layouts.app')
@section('title', isset($budget) ? 'Edit Anggaran' : 'Tambah Anggaran')
@section('page-title', isset($budget) ? 'Edit Anggaran' : 'Tambah Anggaran')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form method="POST" action="{{ isset($budget) ? route('budgets.update', $budget) : route('budgets.store') }}">
            @csrf
            @if(isset($budget)) @method('PUT') @endif
            <div class="space-y-5">
                @if(!isset($budget))
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kategori Pengeluaran</label>
                    <select name="category_id" id="category_id" required class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                @else
                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50">
                        <p class="text-xs text-gray-500">Kategori</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $budget->category->name }}</p>
                    </div>
                @endif

                <div>
                    <label for="limit_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Batas Anggaran</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                        <input type="number" name="limit_amount" id="limit_amount" value="{{ old('limit_amount', $budget->limit_amount ?? '') }}" step="1" min="1" required
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    @error('limit_amount') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                @if(!isset($budget))
                <div>
                    <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Periode</label>
                    <input type="month" name="period" id="period" value="{{ old('period', now()->format('Y-m')) }}" required
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('period') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                @endif
            </div>
            <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('budgets.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">{{ isset($budget) ? 'Simpan' : 'Tambah' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
