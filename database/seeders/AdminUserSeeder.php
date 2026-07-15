<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@cardx.com'],
            [
                'name' => 'مدیر سیستم',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('super-admin');

        // Create a demo customer
        $customer = User::firstOrCreate(
            ['email' => 'user@cardx.com'],
            [
                'name' => 'کاربر نمونه',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $customer->assignRole('customer');
    }
}
