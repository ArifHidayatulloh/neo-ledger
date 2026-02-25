@extends('layouts.app')

@section('title', isset($transaction) ? 'Edit Transaksi' : 'Tambah Transaksi')
@section('page-title', isset($transaction) ? 'Edit Transaksi' : 'Tambah Transaksi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form method="POST" action="{{ isset($transaction) ? route('transactions.update', $transaction) : route('transactions.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($transaction))
                @method('PUT')
            @endif

            <div class="space-y-5">
                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Transaksi</label>
                    <div class="flex gap-3">
                        <label class="flex-1 relative">
                            <input type="radio" name="type" value="income" class="peer sr-only" {{ old('type', $transaction->type ?? '') === 'income' ? 'checked' : '' }} required>
                            <div class="p-3 text-center rounded-xl border-2 border-gray-200 dark:border-gray-600 cursor-pointer transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 peer-checked:text-emerald-700 dark:peer-checked:text-emerald-400 text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-500">
                                <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                                <span class="text-sm font-medium">Pemasukan</span>
                            </div>
                        </label>
                        <label class="flex-1 relative">
                            <input type="radio" name="type" value="expense" class="peer sr-only" {{ old('type', $transaction->type ?? '') === 'expense' ? 'checked' : '' }}>
                            <div class="p-3 text-center rounded-xl border-2 border-gray-200 dark:border-gray-600 cursor-pointer transition-all peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/20 peer-checked:text-rose-700 dark:peer-checked:text-rose-400 text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-500">
                                <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.51l-5.511-3.181"/></svg>
                                <span class="text-sm font-medium">Pengeluaran</span>
                            </div>
                        </label>
                    </div>
                    @error('type') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nominal</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                        <input type="number" name="amount" id="amount" value="{{ old('amount', isset($transaction) ? $transaction->amount : '') }}" step="0.01" min="0.01" required
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="0">
                    </div>
                    @error('amount') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Account -->
                <div>
                    <label for="account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Akun / Rekening</label>
                    <select name="account_id" id="account_id" required class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Pilih Akun</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('account_id', $transaction->account_id ?? '') == $account->id ? 'selected' : '' }}>
                                {{ $account->account_name }} (Rp {{ number_format($account->current_balance, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('account_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Category (filtered by type via JS) -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kategori</label>
                    <select name="category_id" id="category_id" required class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" data-type="{{ $category->type }}" {{ old('category_id', $transaction->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="transaction_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal</label>
                    <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', isset($transaction) ? $transaction->transaction_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('transaction_date') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                        placeholder="Keterangan transaksi...">{{ old('description', $transaction->description ?? '') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Reference Number -->
                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nomor Referensi <span class="text-gray-400">(opsional)</span></label>
                    <input type="text" name="reference_number" id="reference_number" value="{{ old('reference_number', $transaction->reference_number ?? '') }}"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="INV-001, TRF-xxx, dll.">
                    @error('reference_number') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Attachments -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Lampiran <span class="text-gray-400">(opsional, maks. 5 file)</span></label>
                    <div class="relative">
                        <input type="file" name="attachments[]" id="attachments" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx"
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50">
                    </div>
                    <p class="mt-1 text-xs text-gray-400">JPG, PNG, GIF, PDF, DOC, XLS — maks. 5MB per file</p>
                    @error('attachments') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    @error('attachments.*') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('transactions.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">
                    {{ isset($transaction) ? 'Simpan Perubahan' : 'Simpan Transaksi' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Filter categories based on selected type
document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const categorySelect = document.getElementById('category_id');
    const allOptions = [...categorySelect.querySelectorAll('option[data-type]')];

    function filterCategories() {
        const selectedType = document.querySelector('input[name="type"]:checked');
        if (!selectedType) return;

        const currentValue = categorySelect.value;
        // Remove all options except the placeholder
        allOptions.forEach(opt => opt.remove());

        // Add back matching options
        allOptions.filter(opt => opt.dataset.type === selectedType.value).forEach(opt => {
            categorySelect.appendChild(opt);
        });

        // Restore selection if still valid
        if (categorySelect.querySelector(`option[value="${currentValue}"]`)) {
            categorySelect.value = currentValue;
        } else {
            categorySelect.value = '';
        }
    }

    typeRadios.forEach(radio => radio.addEventListener('change', filterCategories));
    // Initial filter
    if (document.querySelector('input[name="type"]:checked')) {
        filterCategories();
    }
});
</script>
@endpush
