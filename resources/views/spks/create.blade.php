@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('spks.store') }}">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="spk_ref" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">SPK Ref</label>
                    <input name="spk_ref" id="spk_ref" value="{{ old('spk_ref') }}" required class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('spk_ref') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Client</label>
                    <select name="client_id" id="client_id" class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">- Pilih Klien -</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="total_contract" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Total Contract</label>
                        <input name="total_contract" id="total_contract" type="number" value="{{ old('total_contract', 0) }}" class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('total_contract') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="dp_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">DP Amount</label>
                        <input name="dp_amount" id="dp_amount" type="number" value="{{ old('dp_amount', 0) }}" class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('dp_amount') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="context" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Konteks / Keterangan (opsional)</label>
                    <textarea name="context" id="context" rows="3" class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('context') }}</textarea>
                    @error('context') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('spks.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">Buat SPK</button>
            </div>
        </form>
    </div>
</div>
@endsection
