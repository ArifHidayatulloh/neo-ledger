<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'permissions' => ['*'],
            ],
            [
                'name' => 'approver',
                'permissions' => [
                    'dashboard.view',
                    'transactions.view', 'transactions.create', 'transactions.edit', 'transactions.approve',
                    'accounts.view',
                    'categories.view',
                    'budgets.view', 'budgets.manage',
                    'reports.view', 'reports.export',
                    'recurring.view', 'recurring.create',
                ],
            ],
            [
                'name' => 'editor',
                'permissions' => [
                    'dashboard.view',
                    'transactions.view', 'transactions.create', 'transactions.edit',
                    'accounts.view',
                    'categories.view',
                    'budgets.view',
                    'reports.view', 'reports.export',
                    'recurring.view', 'recurring.create',
                ],
            ],
            [
                'name' => 'viewer',
                'permissions' => [
                    'dashboard.view',
                    'transactions.view',
                    'accounts.view',
                    'categories.view',
                    'budgets.view',
                    'reports.view',
                ],
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
