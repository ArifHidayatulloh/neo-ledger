@extends('layouts.app')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Approval Settings -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Pengaturan Approval</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Atur batas minimum transaksi yang memerlukan persetujuan</p>

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                @foreach($settings as $i => $setting)
                    <div class="p-4 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <input type="hidden" name="settings[{{ $i }}][id]" value="{{ $setting->id }}">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white capitalize">{{ $setting->transaction_type }}</h4>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="settings[{{ $i }}][is_active]" value="1" {{ $setting->is_active ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Minimum Amount</label>
                                <div class="relative">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">Rp</span>
                                    <input type="number" name="settings[{{ $i }}][threshold_amount]" value="{{ $setting->threshold_amount }}" class="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Approver Role</label>
                                <select name="settings[{{ $i }}][approver_role_id]" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $setting->approver_role_id == $role->id ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>
@endsection
