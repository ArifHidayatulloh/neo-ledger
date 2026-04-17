@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-xl font-semibold">{{ $spk->spk_ref }}</h2>
                <div class="text-sm text-gray-500">Client: {{ $spk->client->name ?? '-' }}</div>
            </div>
            <div>
                <a href="{{ route('spks.edit', $spk->id) }}" class="px-3 py-1.5 bg-indigo-600 text-white rounded text-sm">Edit</a>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-400">Total Contract</div>
                <div class="font-medium">{{ number_format($spk->total_contract,0,',','.') }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400">Final Bill</div>
                <div class="font-medium">{{ number_format($spk->final_bill,0,',','.') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
