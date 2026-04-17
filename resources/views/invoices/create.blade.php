@extends('layouts.app')

@section('title', 'Buat Invoice')
@section('page-title', 'Buat Invoice')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('invoices.store') }}">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Client</label>
                    <select name="client_id" id="client_id" required class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Pilih Klien</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}" {{ (old('client_id') == $c->id) || (isset($spk) && $spk->client_id == $c->id && !old('client_id')) ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="spk_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">SPK (opsional)</label>
                    <select name="spk_id" id="spk_id" class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Pilih SPK (opsional)</option>
                        @foreach($spks as $s)
                            <option value="{{ $s->id }}" data-client="{{ $s->client_id }}" data-total="{{ $s->total_contract }}" data-dp="{{ $s->dp_amount }}" data-final="{{ $s->final_bill }}" data-context="{{ $s->context }}" {{ isset($spk) && $spk->id == $s->id ? 'selected' : (old('spk_id') == $s->id ? 'selected' : '') }}>{{ $s->spk_ref }}</option>
                        @endforeach
                    </select>
                    @error('spk_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Total Kontrak</label>
                        <div id="total_contract" class="px-3 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">-</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">DP Diterima</label>
                        <div id="dp_amount" class="px-3 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">-</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Final Bill</label>
                        <div id="final_bill" class="px-3 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">-</div>
                    </div>
                </div>

                <div>
                    <label for="bank_account" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Bank Account (untuk invoice ini)</label>
                    <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account') }}" placeholder="Bank Mandiri a.n Arif Hidayatulloh" class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('bank_account') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Bank Name (opsional)</label>
                        <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" placeholder="Bank Mandiri" class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('bank_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="bank_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Bank Number (opsional)</label>
                        <input type="text" name="bank_number" id="bank_number" value="{{ old('bank_number') }}" placeholder="123-456-789" class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('bank_number') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Due Date (opsional)</label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('due_date') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div id="confirm_unpaid_wrapper" class="hidden">
                    <label class="inline-flex items-start gap-2">
                        <input type="checkbox" name="confirm_unpaid" id="confirm_unpaid" class="mt-1">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Saya mengonfirmasi membuat invoice untuk SPK yang belum lunas.</span>
                    </label>
                    @error('confirm_unpaid') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Preview tab -->
                <div>
                    <div class="mt-4">
                        <nav class="flex gap-2 mb-3">
                            <button type="button" id="tab_form" class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-sm">Form</button>
                            <button type="button" id="tab_preview" class="px-3 py-1 rounded-full text-sm">Preview</button>
                        </nav>

                        <div id="preview_panel" class="hidden bg-gray-50 dark:bg-gray-700 p-4 rounded-xl">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold">PT. Neo One Global Inovasi</h3>
                                    <div class="text-sm text-gray-500">Project Manager: Arif Hidayatulloh</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Invoice</div>
                                    <div id="preview_invoice_number" class="font-medium">—</div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <div class="text-xs text-gray-400">Bill To</div>
                                    <div id="preview_client" class="font-medium">-</div>
                                    <div id="preview_client_contact" class="text-sm text-gray-500"></div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400">SPK</div>
                                    <div id="preview_spk" class="font-medium">-</div>
                                    <div id="preview_spk_context" class="text-sm text-gray-500 mt-1">-</div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-xs text-gray-500">
                                            <th>Description</th>
                                            <th class="text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Total Contract</td>
                                            <td class="text-right" id="preview_total">-</td>
                                        </tr>
                                        <tr>
                                            <td>DP Received</td>
                                            <td class="text-right" id="preview_dp">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Final Bill</strong></td>
                                            <td class="text-right font-semibold" id="preview_final">-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 text-sm text-gray-500">Payment Info: <span id="preview_bank">-</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('invoices.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">Buat Invoice</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const spkSelect = document.getElementById('spk_id');
    const clientSelect = document.getElementById('client_id');
    const totalEl = document.getElementById('total_contract');
    const dpEl = document.getElementById('dp_amount');
    const finalEl = document.getElementById('final_bill');

    function formatCurrency(val) {
        if (val === null || val === undefined || val === '') return '-';
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(Number(val));
    }

    function updateTotals() {
        const opt = spkSelect.selectedOptions[0];
        if (!opt || !opt.value) {
            totalEl.textContent = '-';
            dpEl.textContent = '-';
            finalEl.textContent = '-';
            return;
        }
        const total = opt.dataset.total || 0;
        const dp = opt.dataset.dp || 0;
        const final = opt.dataset.final || (total - dp);
        totalEl.textContent = formatCurrency(total);
        dpEl.textContent = formatCurrency(dp);
        finalEl.textContent = formatCurrency(final);
        // auto-select client when SPK chosen (if client exists)
        if (opt.dataset.client) {
            // only set if client not already chosen
            if (!clientSelect.value) {
                clientSelect.value = opt.dataset.client;
                clientSelect.dispatchEvent(new Event('change'));
            }
        }
    }

    spkSelect.addEventListener('change', updateTotals);
    // initial
    updateTotals();
});
</script>
@endpush

@push('scripts')
<script>
// Tab switching and preview update
document.addEventListener('DOMContentLoaded', function() {
    const tabForm = document.getElementById('tab_form');
    const tabPreview = document.getElementById('tab_preview');
    const previewPanel = document.getElementById('preview_panel');
    const confirmWrapper = document.getElementById('confirm_unpaid_wrapper');
    const spkSelect = document.getElementById('spk_id');
    const clientSelect = document.getElementById('client_id');

    function showForm() {
        previewPanel.classList.add('hidden');
        tabForm.classList.add('bg-indigo-50','text-indigo-600');
        tabPreview.classList.remove('bg-indigo-50','text-indigo-600');
    }
    function showPreview() {
        previewPanel.classList.remove('hidden');
        tabPreview.classList.add('bg-indigo-50','text-indigo-600');
        tabForm.classList.remove('bg-indigo-50','text-indigo-600');
        updatePreview();
    }

    tabForm.addEventListener('click', showForm);
    tabPreview.addEventListener('click', showPreview);

    function updatePreview() {
        const opt = spkSelect.selectedOptions[0];
        const clientOpt = clientSelect.selectedOptions[0];
        document.getElementById('preview_spk').textContent = opt && opt.value ? opt.textContent : '-';
        const ctx = opt && opt.value && opt.dataset.context ? opt.dataset.context : '';
        document.getElementById('preview_spk_context').textContent = ctx || '-';
        document.getElementById('preview_spk_context').title = ctx;
        document.getElementById('preview_client').textContent = clientOpt && clientOpt.value ? clientOpt.textContent : '-';
        document.getElementById('preview_client_contact').textContent = '';
        document.getElementById('preview_total').textContent = document.getElementById('total_contract').textContent;
        document.getElementById('preview_dp').textContent = document.getElementById('dp_amount').textContent;
        document.getElementById('preview_final').textContent = document.getElementById('final_bill').textContent;
        document.getElementById('preview_bank').textContent = document.getElementById('bank_account').value || 'Bank Mandiri a.n Arif Hidayatulloh';
    }

    // Show confirm checkbox if selected SPK has unpaid final
    function toggleConfirm() {
        const opt = spkSelect.selectedOptions[0];
        if (!opt || !opt.value) {
            confirmWrapper.classList.add('hidden');
            return;
        }
        const final = Number(opt.dataset.final || (opt.dataset.total - opt.dataset.dp) || 0);
        if (final > 0) {
            confirmWrapper.classList.remove('hidden');
        } else {
            confirmWrapper.classList.add('hidden');
            document.getElementById('confirm_unpaid').checked = false;
        }
    }

    spkSelect.addEventListener('change', function() { toggleConfirm(); updatePreview(); });
    clientSelect.addEventListener('change', updatePreview);
    document.getElementById('bank_account').addEventListener('input', updatePreview);

    // initial
    toggleConfirm();
});
</script>
@endpush
