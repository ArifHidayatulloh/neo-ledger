<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        Account::create([
            'account_name' => 'Bank Mandiri - PT Neo',
            'account_type' => 'bank',
            'account_number' => '1234567890',
            'current_balance' => 150000000, // 150 jt
        ]);

        Account::create([
            'account_name' => 'Bank BCA - Operasional',
            'account_type' => 'bank',
            'account_number' => '0987654321',
            'current_balance' => 75000000, // 75 jt
        ]);

        Account::create([
            'account_name' => 'Kas Kecil (Petty Cash)',
            'account_type' => 'cash',
            'current_balance' => 5000000, // 5 jt
        ]);
    }
}
