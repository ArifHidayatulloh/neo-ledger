<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Transaksi</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin: 0; color: #4F46E5; }
        .header p { font-size: 11px; color: #666; margin: 4px 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #4F46E5; color: #fff; padding: 8px 6px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 6px; border-bottom: 1px solid #E5E7EB; }
        tr:nth-child(even) td { background-color: #F9FAFB; }
        .amount { text-align: right; font-weight: bold; }
        .income { color: #059669; }
        .expense { color: #DC2626; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .badge-approved { background: #D1FAE5; color: #065F46; }
        .badge-pending { background: #FEF3C7; color: #92400E; }
        .badge-rejected { background: #FEE2E2; color: #991B1B; }
        .footer { margin-top: 20px; text-align: center; color: #9CA3AF; font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>NeoLedger — Laporan Transaksi</h1>
        <p>Diekspor pada {{ now()->format('d M Y H:i') }} WIB</p>
        @if(!empty($filters['date_from']) || !empty($filters['date_to']))
            <p>Periode: {{ $filters['date_from'] ?? '...' }} s/d {{ $filters['date_to'] ?? '...' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Kategori</th>
                <th>Akun</th>
                <th style="text-align:right">Jumlah (Rp)</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Dibuat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
                <tr>
                    <td>{{ $tx->transaction_date?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                    <td>{{ $tx->category->name ?? '-' }}</td>
                    <td>{{ $tx->account->account_name ?? '-' }}</td>
                    <td class="amount {{ $tx->type }}">{{ number_format($tx->amount, 0, ',', '.') }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($tx->description, 40) ?? '-' }}</td>
                    <td><span class="badge badge-{{ $tx->status }}">{{ ucfirst($tx->status) }}</span></td>
                    <td>{{ $tx->user->name ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center; padding:20px;">Tidak ada data transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>NeoLedger Cashflow Manager &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
