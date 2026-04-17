@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-xl font-semibold">{{ $client->name }}</h2>
                <div class="text-sm text-gray-500">{{ $client->contact_person }} — {{ $client->phone }}</div>
            </div>
            <div>
                <a href="{{ route('clients.edit', $client->id) }}" class="px-3 py-1.5 bg-indigo-600 text-white rounded text-sm">Edit</a>
            </div>
        </div>

        <div class="mt-4 text-sm text-gray-600">{{ $client->email }}</div>
        <div class="mt-4 text-sm text-gray-500">{{ $client->address }}</div>
    </div>
</div>
@endsection
