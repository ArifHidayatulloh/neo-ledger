<?php

namespace App\Http\Controllers;

use App\Models\ApprovalSetting;
use App\Models\AuditLog;
use App\Models\Role;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = ApprovalSetting::with('approverRole')->get();
        $roles = Role::all();

        return view('settings.index', compact('settings', 'roles'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.id' => 'required|exists:approval_settings,id',
            'settings.*.threshold_amount' => 'required|numeric|min:0',
            'settings.*.approver_role_id' => 'required|exists:roles,id',
            'settings.*.is_active' => 'sometimes|boolean',
        ]);

        foreach ($validated['settings'] as $data) {
            $setting = ApprovalSetting::find($data['id']);
            $oldValues = $setting->only(['threshold_amount', 'approver_role_id', 'is_active']);

            $setting->update([
                'threshold_amount' => $data['threshold_amount'],
                'approver_role_id' => $data['approver_role_id'],
                'is_active' => $data['is_active'] ?? false,
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'update',
                'auditable_type' => ApprovalSetting::class,
                'auditable_id' => $setting->id,
                'old_values' => $oldValues,
                'new_values' => ['threshold_amount' => $data['threshold_amount'], 'approver_role_id' => $data['approver_role_id'], 'is_active' => $data['is_active'] ?? false],
            ]);
        }

        return back()->with('success', 'Pengaturan approval berhasil disimpan.');
    }
}
