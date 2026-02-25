<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AccountSeeder::class,
            CategorySeeder::class,
            ApprovalSettingSeeder::class,
            DemoTransactionSeeder::class,
        ]);
    }
}
