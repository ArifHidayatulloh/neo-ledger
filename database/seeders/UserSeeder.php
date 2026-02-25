<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $approverRole = Role::where('name', 'approver')->first();
        $viewerRole = Role::where('name', 'viewer')->first();

        User::create([
            'name' => 'Admin NeoLedger',
            'email' => 'admin@neoledger.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Finance Manager',
            'email' => 'manager@neoledger.com',
            'password' => bcrypt('password'),
            'role_id' => $approverRole->id,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Staff Viewer',
            'email' => 'viewer@neoledger.com',
            'password' => bcrypt('password'),
            'role_id' => $viewerRole->id,
            'email_verified_at' => now(),
        ]);
    }
}
