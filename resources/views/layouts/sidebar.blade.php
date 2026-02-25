@php
    $menuItems = [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'home',
            'permission' => 'dashboard.view',
        ],
        [
            'label' => 'Transaksi',
            'route' => 'transactions.index',
            'icon' => 'arrows-right-left',
            'permission' => 'transactions.view',
        ],
        [
            'label' => 'Akun / Rekening',
            'route' => 'accounts.index',
            'icon' => 'building-library',
            'permission' => 'accounts.view',
        ],
        [
            'label' => 'Kategori',
            'route' => 'categories.index',
            'icon' => 'tag',
            'permission' => 'categories.view',
        ],
        [
            'label' => 'Anggaran',
            'route' => 'budgets.index',
            'icon' => 'chart-pie',
            'permission' => 'budgets.view',
        ],
        [
            'label' => 'Recurring',
            'route' => 'recurring.index',
            'icon' => 'arrow-path',
            'permission' => 'recurring.view',
        ],
        [
            'label' => 'Laporan',
            'route' => 'reports.index',
            'icon' => 'document-chart-bar',
            'permission' => 'reports.view',
        ],
    ];

    $adminItems = [
        [
            'label' => 'User Management',
            'route' => 'users.index',
            'icon' => 'users',
            'permission' => 'users.manage',
        ],
        [
            'label' => 'Audit Log',
            'route' => 'audit-logs.index',
            'icon' => 'clipboard-document-list',
            'permission' => 'audit.view',
        ],
        [
            'label' => 'Pengaturan',
            'route' => 'settings.index',
            'icon' => 'cog-6-tooth',
            'permission' => 'settings.manage',
        ],
    ];
@endphp

<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-indigo-900/50">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">NeoLedger</h1>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-widest font-medium">Cashflow Manager</p>
            </div>
            <!-- Close button mobile -->
            <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <p class="px-3 mb-2 text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Menu Utama</p>

            @foreach($menuItems as $item)
                @if(auth()->user()->hasPermission($item['permission']))
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                       {{ Route::currentRouteName() === $item['route']
                           ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 shadow-sm'
                           : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                        @include('components.icons.' . $item['icon'], ['class' => 'w-5 h-5 shrink-0'])
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endif
            @endforeach

            @php $hasAdminItems = collect($adminItems)->filter(fn($i) => auth()->user()->hasPermission($i['permission']))->isNotEmpty(); @endphp
            @if($hasAdminItems)
                <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="px-3 mb-2 text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Administrasi</p>

                    @foreach($adminItems as $item)
                        @if(auth()->user()->hasPermission($item['permission']))
                            <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                               class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                               {{ Route::currentRouteName() === $item['route']
                                   ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 shadow-sm'
                                   : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                                @include('components.icons.' . $item['icon'], ['class' => 'w-5 h-5 shrink-0'])
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </nav>

        <!-- User Info at Bottom -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-sm font-bold shadow-md">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ auth()->user()->role->name }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>
