<table>
    <thead>
        <tr>
            <th>Invoice #</th>
            <th>SPK</th>
            <th>Client</th>
            <th>Final Bill</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $inv)
            <tr>
                <td>{{ $inv->invoice_number }}</td>
                <td>{{ $inv->spk->spk_ref ?? '-' }}</td>
                <td>{{ $inv->client->name ?? '-' }}</td>
                <td>{{ number_format($inv->final_bill,2) }}</td>
                <td>{{ ucfirst($inv->status) }}</td>
                <td>{{ $inv->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
