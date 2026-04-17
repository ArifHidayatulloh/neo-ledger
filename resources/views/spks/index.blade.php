@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola SPK dan status proyek</p>
            </div>
            <div class="flex items-center gap-2">
                @if (auth()->user()->hasPermission('spks.create'))
                    <a href="{{ route('spks.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah SPK
                    </a>
                @endif
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <form method="GET" action="{{ route('spks.index') }}"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-3">
                <!-- Search -->
                <div class="xl:col-span-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari spk / client..."
                        class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-sm font-medium rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('spks.index') }}"
                        class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        title="Reset">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                        </svg>
                    </a>
                </div>
            </form>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">

                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            <th
                                class="py-3 px-5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                SPK Ref</th>
                            <th
                                class="py-3 px-5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Client</th>
                            <th
                                class="py-3 px-5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total</th>
                            <th
                                class="py-3 px-5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Final Bill</th>
                            <th
                                class="py-3 px-5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Keterangan</th>
                            <th
                                class="py-3 px-5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider align-middle">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($spks as $s)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-3 px-5 text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $s->spk_ref }}
                                </td>
                                <td class="py-3 px-5 text-gray-600 dark:text-gray-400">{{ $s->client->name ?? '-' }}</td>
                                <td class="py-3 px-5">{{ number_format($s->total_contract, 0, ',', '.') }}</td>
                                <td class="py-3 px-5">{{ number_format($s->final_bill, 0, ',', '.') }}</td>
                                <td class="py-3 px-5 text-gray-600 dark:text-gray-400">
                                    @if($s->context)
                                        <span title="{{ $s->context }}">{{ \Illuminate\Support\Str::limit($s->context, 80) }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 px-5 text-center align-middle">
                                    <div class="flex items-center justify-center gap-1">
                                            <a href="{{ route('spks.edit', $s->id) }}"
                                                class="inline-flex h-9 w-9 items-center justify-center p-0 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                title="Edit">
                                                <svg class="w-5 h-5 block" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('spks.show', $s->id) }}"
                                            class="inline-flex h-9 w-9 items-center justify-center p-0 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                            title="Detail">
                                            <svg class="w-5 h-5 block" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $spks->links() }}</div>
        </div>
    </div>
@endsection
