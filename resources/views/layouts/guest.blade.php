<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NeoLedger') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- Left Panel — Branding -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-purple-950">
                <!-- Animated background orbs -->
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute top-1/3 right-0 w-80 h-80 bg-purple-500/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
                    <div class="absolute bottom-0 left-1/4 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
                </div>

                <!-- Grid pattern overlay -->
                <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle, rgba(255,255,255,0.15) 1px, transparent 1px); background-size: 40px 40px;"></div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                    <div>
                        <!-- Logo -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">                                    
                                     <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-white tracking-tight">NeoLedger</span>
                        </div>
                    </div>

                    <!-- Middle — Feature highlights -->
                    <div class="space-y-8">
                        <div>
                            <h1 class="text-4xl font-bold text-white leading-tight">
                                Kelola Keuangan<br>
                                <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Lebih Cerdas.</span>
                            </h1>
                            <p class="mt-4 text-lg text-slate-400 max-w-md">
                                Platform manajemen keuangan modern dengan approval workflow, laporan real-time, dan kontrol penuh atas transaksi Anda.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                </div>
                                <span class="text-sm text-slate-300">Multi-akun dengan tracking saldo real-time</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                </div>
                                <span class="text-sm text-slate-300">Approval workflow otomatis berdasarkan threshold</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                </div>
                                <span class="text-sm text-slate-300">Laporan & analisis keuangan komprehensif</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom — Testimonial / tagline -->
                    <div class="border-t border-white/10 pt-6">
                        <p class="text-sm text-slate-500">&copy; {{ date('Y') }} NeoLedger. Modern Financial Management.</p>
                    </div>
                </div>
            </div>

            <!-- Right Panel — Form -->
            <div class="w-full lg:w-1/2 flex flex-col items-center justify-center px-6 py-12 bg-gray-50 dark:bg-gray-900 relative">
                <!-- Mobile logo (shown when left panel is hidden) -->
                <div class="lg:hidden mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">NeoLedger</span>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>

                <!-- Bottom link for mobile -->
                <p class="lg:hidden mt-8 text-xs text-gray-400">&copy; {{ date('Y') }} NeoLedger</p>
            </div>
        </div>
    </body>
</html>
