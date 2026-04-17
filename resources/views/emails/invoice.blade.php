<p>Yth. {{ $invoice->client->contact_person ?? $invoice->client->name }},</p>

<p>Terlampir invoice untuk pelunasan pekerjaan dengan nomor <strong>{{ $invoice->invoice_number }}</strong>. Mohon lakukan pembayaran sesuai instruksi.</p>

<p>Detail pembayaran:<br>
Bank: {{ $invoice->bank_account ?? 'Bank Mandiri' }}<br>
Atas Nama: Arif Hidayatulloh</p>

<p>Terima kasih.</p>
