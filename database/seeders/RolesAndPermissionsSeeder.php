<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            ['model' => 'Users', 'permissions' => ['View', 'Create', 'Edit', 'Delete', 'Restore', 'Export']],
            ['model' => 'Roles & Permission', 'permissions' => ['View', 'Create', 'Edit', 'Delete', 'Restore', 'Export', 'Import']],
            ['model' => 'Products', 'permissions' => ['View', 'Create', 'Edit', 'Delete', 'Restore', 'Export']],
            ['model' => 'Customers', 'permissions' => ['View', 'Create', 'Edit', 'Delete', 'Restore', 'Export']],
            ['model' => 'Settings', 'permissions' => ['View', 'Edit']],
        ];

        // Loop through modules and create permissions
        foreach ($modules as $module) {
            foreach ($module['permissions'] as $permission) {
                Permission::firstOrCreate([
                    'name' => strtolower($permission) . '-' . strtolower(str_replace(' ', '-', $module['model'])),
                    'guard_name' => 'web',
                ]);
            }
        }

        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Give all perms to admin
        $admin->givePermissionTo(Permission::all());

        // Give limited perms to user
        $user->givePermissionTo([
            'view-users',
            'view-products',
        ]);

        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'aswinproplus@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
            ]
        );

        $adminUser->assignRole('admin');

        // Create normal user
        $normalUser = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User',
                'password' => bcrypt('user123'),
            ]
        );

        $normalUser->assignRole('user');
    }

}