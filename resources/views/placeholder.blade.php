@extends('layouts.app')

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="flex flex-col items-center justify-center py-20">
    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.648 3.327a.75.75 0 01-1.029-.465l-.065-.189a9.001 9.001 0 0111.323-12.066l.189.065a.75.75 0 01.465 1.029L13.34 12.52a3.001 3.001 0 01-1.92 2.651z" />
        </svg>
    </div>
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $title }}</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400">Halaman ini akan segera tersedia. 🚀</p>
</div>
@endsection
