<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Audit Log</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin: 0; color: #4F46E5; }
        .header p { font-size: 11px; color: #666; margin: 4px 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #4F46E5; color: #fff; padding: 8px 6px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 6px; border-bottom: 1px solid #E5E7EB; font-size: 9px; }
        tr:nth-child(even) td { background-color: #F9FAFB; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .badge-create { background: #D1FAE5; color: #065F46; }
        .badge-update { background: #DBEAFE; color: #1E40AF; }
        .badge-delete { background: #FEE2E2; color: #991B1B; }
        .footer { margin-top: 20px; text-align: center; color: #9CA3AF; font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>NeoLedger — Audit Log</h1>
        <p>Diekspor pada {{ now()->format('d M Y H:i') }} WIB</p>
        @if(!empty($filters['date_from']) || !empty($filters['date_to']))
            <p>Periode: {{ $filters['date_from'] ?? '...' }} s/d {{ $filters['date_to'] ?? '...' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>User</th>
                <th>Aksi</th>
                <th>Target</th>
                <th>Detail</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td><span class="badge badge-{{ $log->action }}">{{ ucfirst($log->action) }}</span></td>
                    <td>{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</td>
                    <td>{{ $log->new_values ? \Illuminate\Support\Str::limit(json_encode($log->new_values), 80) : '-' }}</td>
                    <td>{{ $log->ip_address ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center; padding:20px;">Tidak ada data audit log.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>NeoLedger Cashflow Manager &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
