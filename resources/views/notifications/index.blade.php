@extends('layouts.app')
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ $notifications->total() }} notifikasi
            @if($unreadCount > 0)
                <span class="text-indigo-600 dark:text-indigo-400 font-medium">({{ $unreadCount }} belum dibaca)</span>
            @endif
        </p>
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    Tandai semua dibaca
                </button>
            </form>
        @endif
    </div>

    <!-- Notification List -->
    @forelse($notifications as $notif)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border {{ !$notif->is_read ? 'border-l-4 border-l-indigo-500 border-gray-100 dark:border-gray-700' : 'border-gray-100 dark:border-gray-700' }} p-4 flex items-start gap-4 transition-all hover:shadow-md">
            <!-- Icon -->
            <div class="flex-shrink-0 mt-0.5">
                @switch($notif->type)
                    @case('transaction_pending')
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        </div>
                        @break
                    @case('transaction_approved')
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        </div>
                        @break
                    @case('transaction_rejected')
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        </div>
                        @break
                    @default
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
                        </div>
                @endswitch
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white {{ !$notif->is_read ? '' : 'font-medium' }}">{{ $notif->title }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $notif->message }}</p>
                    </div>
                    @if(!$notif->is_read)
                        <span class="flex-shrink-0 w-2.5 h-2.5 bg-indigo-500 rounded-full mt-1.5"></span>
                    @endif
                </div>
                <div class="flex items-center gap-3 mt-2">
                    <p class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</p>
                    @if($notif->reference_type === 'transaction' && $notif->reference_id)
                        <a href="{{ route('notifications.show', $notif) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Lihat Transaksi →</a>
                    @endif
                    @if(!$notif->is_read)
                        <form method="POST" action="{{ route('notifications.mark-read', $notif) }}" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Tandai dibaca</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="py-16 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
            </div>
            <p class="text-gray-400 dark:text-gray-500 font-medium">Belum ada notifikasi</p>
            <p class="text-sm text-gray-300 dark:text-gray-600 mt-1">Notifikasi akan muncul saat ada aktivitas transaksi</p>
        </div>
    @endforelse

    @if($notifications->hasPages())
        <div class="mt-4">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
