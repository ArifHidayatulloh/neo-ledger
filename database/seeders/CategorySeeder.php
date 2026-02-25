<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Income categories
            ['name' => 'Revenue Proyek', 'type' => 'income', 'icon' => 'briefcase', 'color' => '#10B981'],
            ['name' => 'Jasa Konsultasi', 'type' => 'income', 'icon' => 'chat-bubble-left-right', 'color' => '#06B6D4'],
            ['name' => 'Pendapatan Lainnya', 'type' => 'income', 'icon' => 'banknotes', 'color' => '#8B5CF6'],

            // Expense categories
            ['name' => 'Gaji Karyawan', 'type' => 'expense', 'icon' => 'users', 'color' => '#F43F5E'],
            ['name' => 'Infrastruktur Server', 'type' => 'expense', 'icon' => 'server-stack', 'color' => '#EF4444'],
            ['name' => 'Langganan SaaS', 'type' => 'expense', 'icon' => 'cloud', 'color' => '#F97316'],
            ['name' => 'Marketing', 'type' => 'expense', 'icon' => 'megaphone', 'color' => '#EC4899'],
            ['name' => 'Operasional Kantor', 'type' => 'expense', 'icon' => 'building-office', 'color' => '#EAB308'],
            ['name' => 'Transport & Perjalanan', 'type' => 'expense', 'icon' => 'truck', 'color' => '#14B8A6'],
            ['name' => 'Lain-lain', 'type' => 'expense', 'icon' => 'ellipsis-horizontal', 'color' => '#6B7280'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
