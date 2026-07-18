<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin - all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin - most permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::where('name', '!=', 'settings.edit')->get());

        // Manager
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions([
            'users.view',
            'qr_codes.view', 'qr_codes.create', 'qr_codes.edit', 'qr_codes.download',
            'media.view', 'media.upload',
            'analytics.view',
            'reports.view',
        ]);

        // Support
        $support = Role::firstOrCreate(['name' => 'support']);
        $support->syncPermissions([
            'users.view',
            'qr_codes.view',
            'analytics.view',
            'reports.view',
        ]);

        // Customer
        $customer = Role::firstOrCreate(['name' => 'customer']);
        $customer->syncPermissions([
            'qr_codes.view', 'qr_codes.create', 'qr_codes.delete', 'qr_codes.download',
            'media.view', 'media.upload', 'media.delete',
            'analytics.view',
        ]);
    }
}
