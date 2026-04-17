<style>
    /* Footer */
        .invoice-footer {
            margin-top: 28px;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }
</style>

<div class="max-w-5xl mx-auto w-full bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
    @php
        $logoSrcVar = $logoSrc ?? (file_exists(public_path('images/logo.jpg')) ? asset('images/logo.jpg') : null);
    @endphp
    <div class="flex items-center justify-between invoice-header-row">
        <div class="flex items-center justify-center invoice-header-logo">
            @if($logoSrcVar)
                <img src="{{ $logoSrcVar }}" alt="logo" class="h-12 w-auto">
            @endif
        </div>

        <div class="text-right invoice-header-meta">
            <div class="text-sm text-gray-500 uppercase">FAKTUR</div>
            <div class="text-lg font-semibold">No. {{ $invoice->invoice_number }}</div>

            <div class="text-sm text-gray-500">Tanggal: {{ $invoice->created_at->format('d M Y') }}</div>
            @if($invoice->due_date)
                <div class="text-sm text-rose-600">Jatuh Tempo: {{ $invoice->due_date->format('d M Y') }}</div>
            @endif

        </div>
    </div>

    <div class="border-t border-gray-100 dark:border-gray-700 my-6"></div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div>
            <div class="text-xs text-gray-400 mb-1">Tagihan Kepada</div>
            <div class="font-medium">{{ $invoice->client->name ?? '-' }}</div>
            <div class="text-sm text-gray-500">{{ $invoice->client->contact_person ?? '' }}</div>
            <div class="text-sm text-gray-500">{{ $invoice->client->address ?? '' }}</div>
        </div>
    </div>

    <div class="mt-6">
        <div class="mb-4 text-sm text-gray-700">
            @if($invoice->spk)
                <div>Invoice ini diterbitkan sebagai tagihan untuk pekerjaan terkait SPK <strong>{{ $invoice->spk->spk_ref }}</strong>.</div>
                @if($invoice->spk->context)
                    <div class="mt-2 text-sm text-gray-700"><strong>Keterangan SPK:</strong> {{ $invoice->spk->context }}</div>
                @endif
            @else
                <div>Invoice ini diterbitkan sebagai tagihan atas layanan atau pekerjaan yang telah disepakati.</div>
            @endif
            <div class="mt-2">Mohon melakukan pembayaran sesuai informasi pada bagian <strong>Informasi Pembayaran</strong> sebelum tanggal jatuh tempo.</div>
        </div>

        <table class="w-full border-collapse">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b">
                    <th class="py-3">Deskripsi</th>
                    <th class="py-3 text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="py-3">Total Kontrak</td>
                    <td class="py-3 text-right">{{ number_format($invoice->total_contract,2,',','.') }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-3">DP Diterima</td>
                    <td class="py-3 text-right">{{ number_format($invoice->dp_amount,2,',','.') }}</td>
                </tr>
                <tr class="border-t border-gray-200">
                    <td class="py-4 font-semibold">Tagihan Akhir</td>
                    <td class="py-4 text-right font-semibold">{{ number_format($invoice->final_bill,2,',','.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-6 md:flex">
        <div class="w-full md:w-full bg-gray-50 dark:bg-gray-700 p-5 rounded-lg">
            <div class="text-sm font-medium text-gray-700">Informasi Pembayaran</div>
            <div class="mt-3 text-sm text-gray-900">{{ $invoice->bank_account ?? ($invoice->bank_name ?? 'Bank Mandiri a.n Arif Hidayatulloh') }}</div>
            @if($invoice->bank_name)
                <div class="text-xs text-gray-500 mt-1">{{ $invoice->bank_name }}</div>
            @endif
            @if($invoice->bank_number)
                <div class="text-xs text-gray-500 mt-1">No: {{ $invoice->bank_number }}</div>
            @endif
        </div>
    </div>

    {{-- <div class="mt-6 text-sm text-gray-500">
        Dokumen tercetak oleh sistem — tidak memerlukan tanda tangan basah.
    </div> --}}

    <div class="invoice-footer">
        <div>PT. Neo One Global Inovasi • Jl. Kp Utan Jaya, RT 03 RW 03 No.123, Pondok Jaya, Cipayung, Depok, Jawa Barat, Indonesia</div>
        <div>Dokumen ini dicetak otomatis. Untuk pertanyaan, hubungi: admin@neoone.id</div>
    </div>
</div>
