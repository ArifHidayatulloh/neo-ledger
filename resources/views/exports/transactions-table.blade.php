<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Tipe</th>
            <th>Kategori</th>
            <th>Akun</th>
            <th>Jumlah</th>
            <th>Deskripsi</th>
            <th>Status</th>
            <th>Dibuat Oleh</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $tx)
            <tr>
                <td>{{ $tx->transaction_date?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                <td>{{ $tx->category->name ?? '-' }}</td>
                <td>{{ $tx->account->account_name ?? '-' }}</td>
                <td>{{ number_format($tx->amount, 0, ',', '.') }}</td>
                <td>{{ $tx->description ?? '-' }}</td>
                <td>{{ ucfirst($tx->status) }}</td>
                <td>{{ $tx->user->name ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
