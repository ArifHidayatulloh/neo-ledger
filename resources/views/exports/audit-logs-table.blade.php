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
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                <td>{{ $log->user->name ?? 'System' }}</td>
                <td>{{ ucfirst($log->action) }}</td>
                <td>{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</td>
                <td>{{ $log->new_values ? \Illuminate\Support\Str::limit(json_encode($log->new_values), 100) : '-' }}</td>
                <td>{{ $log->ip_address ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
