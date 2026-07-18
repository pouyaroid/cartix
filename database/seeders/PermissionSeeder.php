<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'users' => ['view', 'create', 'edit', 'delete'],
            'qr_codes' => ['view', 'create', 'edit', 'delete', 'download'],
            'media' => ['view', 'upload', 'delete', 'rename'],
            'fonts' => ['view', 'upload', 'delete', 'activate'],
            'plans' => ['view', 'create', 'edit', 'delete'],
            'subscriptions' => ['view', 'manage', 'cancel'],
            'payments' => ['view', 'refund'],
            'settings' => ['view', 'edit'],
            'reports' => ['view', 'export'],
            'analytics' => ['view'],
        ];

        foreach ($groups as $group => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$group}.{$action}"]);
            }
        }
    }
}
