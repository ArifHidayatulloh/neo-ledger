<table>
    <thead>
        <tr>
            <th colspan="4">Laporan Keuangan: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</th>
        </tr>
        <tr><th colspan="4"></th></tr>
        <tr>
            <th>Kategori</th>
            <th>Jumlah Transaksi</th>
            <th>Total</th>
            <th>Persentase</th>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="4"><strong>PEMASUKAN</strong></td></tr>
        @foreach($incomeByCategory as $cat)
            <tr>
                <td>{{ $cat['name'] }}</td>
                <td>{{ $transactions->where('type', 'income')->where('category_id', $transactions->where('type', 'income')->firstWhere(fn($t) => $t->category->name === $cat['name'])?->category_id)->count() }}</td>
                <td>{{ number_format($cat['total'], 0, ',', '.') }}</td>
                <td>{{ $totalIncome > 0 ? round(($cat['total'] / $totalIncome) * 100, 1) : 0 }}%</td>
            </tr>
        @endforeach
        <tr>
            <td><strong>Total Pemasukan</strong></td>
            <td></td>
            <td><strong>{{ number_format($totalIncome, 0, ',', '.') }}</strong></td>
            <td></td>
        </tr>
        <tr><td colspan="4"></td></tr>
        <tr><td colspan="4"><strong>PENGELUARAN</strong></td></tr>
        @foreach($expenseByCategory as $cat)
            <tr>
                <td>{{ $cat['name'] }}</td>
                <td>{{ $transactions->where('type', 'expense')->where('category_id', $transactions->where('type', 'expense')->firstWhere(fn($t) => $t->category->name === $cat['name'])?->category_id)->count() }}</td>
                <td>{{ number_format($cat['total'], 0, ',', '.') }}</td>
                <td>{{ $totalExpense > 0 ? round(($cat['total'] / $totalExpense) * 100, 1) : 0 }}%</td>
            </tr>
        @endforeach
        <tr>
            <td><strong>Total Pengeluaran</strong></td>
            <td></td>
            <td><strong>{{ number_format($totalExpense, 0, ',', '.') }}</strong></td>
            <td></td>
        </tr>
        <tr><td colspan="4"></td></tr>
        <tr>
            <td><strong>Saldo Bersih</strong></td>
            <td></td>
            <td><strong>{{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}</strong></td>
            <td></td>
        </tr>
    </tbody>
</table>
