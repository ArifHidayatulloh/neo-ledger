<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin: 0; color: #4F46E5; }
        .header p { font-size: 11px; color: #666; margin: 4px 0 0; }
        .summary { display: table; width: 100%; margin-bottom: 20px; }
        .summary-item { display: table-cell; width: 33.33%; text-align: center; padding: 12px; }
        .summary-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; }
        .summary-value { font-size: 16px; font-weight: bold; margin-top: 4px; }
        .income-value { color: #059669; }
        .expense-value { color: #DC2626; }
        .net-value { color: #4F46E5; }
        .section-title { font-size: 12px; font-weight: bold; color: #374151; margin: 16px 0 8px; padding-bottom: 4px; border-bottom: 2px solid #E5E7EB; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background-color: #4F46E5; color: #fff; padding: 8px 6px; text-align: left; font-size: 9px; text-transform: uppercase; }
        td { padding: 6px; border-bottom: 1px solid #E5E7EB; }
        tr:nth-child(even) td { background-color: #F9FAFB; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .total-row td { background-color: #EEF2FF !important; font-weight: bold; border-top: 2px solid #4F46E5; }
        .footer { margin-top: 20px; text-align: center; color: #9CA3AF; font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>NeoLedger — Laporan Keuangan</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        <p>Diekspor pada {{ now()->format('d M Y H:i') }} WIB</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total Pemasukan</div>
            <div class="summary-value income-value">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Pengeluaran</div>
            <div class="summary-value expense-value">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Saldo Bersih</div>
            <div class="summary-value net-value">Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Income by Category --}}
    <div class="section-title">Pemasukan per Kategori</div>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th class="text-right">Total (Rp)</th>
                <th class="text-right">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incomeByCategory as $cat)
                <tr>
                    <td>{{ $cat['name'] }}</td>
                    <td class="text-right">{{ number_format($cat['total'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ $totalIncome > 0 ? round(($cat['total'] / $totalIncome) * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align:center">Tidak ada data pemasukan.</td></tr>
            @endforelse
            @if($incomeByCategory->isNotEmpty())
                <tr class="total-row">
                    <td>Total</td>
                    <td class="text-right">{{ number_format($totalIncome, 0, ',', '.') }}</td>
                    <td class="text-right">100%</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Expense by Category --}}
    <div class="section-title">Pengeluaran per Kategori</div>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th class="text-right">Total (Rp)</th>
                <th class="text-right">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenseByCategory as $cat)
                <tr>
                    <td>{{ $cat['name'] }}</td>
                    <td class="text-right">{{ number_format($cat['total'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ $totalExpense > 0 ? round(($cat['total'] / $totalExpense) * 100, 1) : 0 }}%</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align:center">Tidak ada data pengeluaran.</td></tr>
            @endforelse
            @if($expenseByCategory->isNotEmpty())
                <tr class="total-row">
                    <td>Total</td>
                    <td class="text-right">{{ number_format($totalExpense, 0, ',', '.') }}</td>
                    <td class="text-right">100%</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>NeoLedger Cashflow Manager &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
