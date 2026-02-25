@extends('layouts.app')
@section('title', isset($category) ? 'Edit Kategori' : 'Tambah Kategori')
@section('page-title', isset($category) ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form method="POST" action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}">
            @csrf
            @if(isset($category)) @method('PUT') @endif
            <div class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Kategori</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name ?? '') }}" required class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Gaji, Makan, Transport...">
                    @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe</label>
                    <div class="flex gap-3">
                        <label class="flex-1 relative">
                            <input type="radio" name="type" value="income" class="peer sr-only" {{ old('type', $category->type ?? '') === 'income' ? 'checked' : '' }} required>
                            <div class="p-3 text-center rounded-xl border-2 border-gray-200 dark:border-gray-600 cursor-pointer transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 text-gray-500 dark:text-gray-400">
                                <span class="text-sm font-medium">Pemasukan</span>
                            </div>
                        </label>
                        <label class="flex-1 relative">
                            <input type="radio" name="type" value="expense" class="peer sr-only" {{ old('type', $category->type ?? '') === 'expense' ? 'checked' : '' }}>
                            <div class="p-3 text-center rounded-xl border-2 border-gray-200 dark:border-gray-600 cursor-pointer transition-all peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/20 text-gray-500 dark:text-gray-400">
                                <span class="text-sm font-medium">Pengeluaran</span>
                            </div>
                        </label>
                    </div>
                    @error('type') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Ikon <span class="text-gray-400">(emoji)</span></label>
                    <input type="text" name="icon" id="icon" value="{{ old('icon', $category->icon ?? '') }}" class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="💰 🏠 🚗">
                    @error('icon') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Warna</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" id="color" value="{{ old('color', $category->color ?? '#6366F1') }}" class="w-10 h-10 rounded-lg border-0 cursor-pointer">
                        <input type="text" value="{{ old('color', $category->color ?? '#6366F1') }}" class="flex-1 px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono" readonly id="color_text">
                    </div>
                    @error('color') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('categories.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">{{ isset($category) ? 'Simpan' : 'Tambah' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('color').addEventListener('input', function() {
    document.getElementById('color_text').value = this.value;
});
</script>
@endpush
