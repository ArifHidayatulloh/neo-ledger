<?php

namespace Database\Seeders;

use App\Models\ApprovalSetting;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ApprovalSettingSeeder extends Seeder
{
    public function run(): void
    {
        $approverRole = Role::where('name', 'approver')->first();

        ApprovalSetting::create([
            'transaction_type' => 'expense',
            'threshold_amount' => 5000000, // 5 jt
            'approver_role_id' => $approverRole->id,
        ]);

        ApprovalSetting::create([
            'transaction_type' => 'income',
            'threshold_amount' => 10000000, // 10 jt
            'approver_role_id' => $approverRole->id,
        ]);

        ApprovalSetting::create([
            'transaction_type' => 'transfer',
            'threshold_amount' => 10000000, // 10 jt
            'approver_role_id' => $approverRole->id,
        ]);
    }
}
