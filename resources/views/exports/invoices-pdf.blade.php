<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h3>Daftar Invoice</h3>
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
</body>
</html>
