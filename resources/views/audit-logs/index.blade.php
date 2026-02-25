@extends('layouts.app')
@section('title', 'Audit Log')
@section('page-title', 'Audit Log')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="flex flex-wrap items-center gap-3">
            <select name="action" class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">Semua Aksi</option>
                @foreach(['create', 'update', 'delete', 'approve', 'reject', 'transfer'] as $action)
                    <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari" class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai" class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <button type="submit" class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-sm font-medium rounded-xl hover:bg-indigo-100 transition-colors">Filter</button>
            <div class="ml-auto">
                <x-export-button route="export.audit-logs" :filters="request()->only(['action', 'date_from', 'date_to'])" />
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase">User</th>
                        <th class="text-center py-3 px-5 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase">Target</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase">Detail</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="py-3 px-5 text-gray-500 dark:text-gray-400 whitespace-nowrap text-xs">{{ $log->created_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td class="py-3 px-5 text-gray-900 dark:text-white font-medium">{{ $log->user->name ?? 'System' }}</td>
                            <td class="py-3 px-5 text-center">
                                @php
                                    $actionColors = [
                                        'create' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'update' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'delete' => 'bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                        'approve' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'reject' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                        'transfer' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $actionColors[$log->action] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($log->action) }}</span>
                            </td>
                            <td class="py-3 px-5 text-xs text-gray-500 dark:text-gray-400">{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</td>
                            <td class="py-3 px-5 text-xs text-gray-400 max-w-xs truncate">
                                @if($log->new_values)
                                    {{ Str::limit(json_encode($log->new_values), 80) }}
                                @endif
                            </td>
                            <td class="py-3 px-5 text-xs text-gray-400 whitespace-nowrap" title="{{ $log->user_agent }}">{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-12 text-center text-gray-400">Belum ada log</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
@endsection
