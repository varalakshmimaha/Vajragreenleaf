<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create default permissions
        $defaultPermissions = Permission::getDefaultPermissions();

        foreach ($defaultPermissions as $group => $permissions) {
            foreach ($permissions as $perm) {
                Permission::firstOrCreate(
                    ['slug' => $perm['slug']],
                    [
                        'name' => $perm['name'],
                        'group' => $group,
                        'description' => $perm['description'],
                        'is_active' => true,
                    ]
                );
            }
        }

        // Create default roles
        $defaultRoles = Role::getDefaultRoles();

        foreach ($defaultRoles as $roleData) {
            Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }

        // Assign all permissions to Admin role
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::pluck('id')->toArray();
            $adminRole->permissions()->sync($allPermissions);
        }

        // Assign editor permissions to Editor role
        $editorRole = Role::where('slug', 'editor')->first();
        if ($editorRole) {
            $editorPermissions = Permission::whereIn('group', [
                'dashboard', 'pages', 'sections', 'blog', 'portfolio', 'team',
                'testimonials', 'gallery', 'menus'
            ])->pluck('id')->toArray();
            $editorRole->permissions()->sync($editorPermissions);
        }

        // Assign author permissions to Author role
        $authorRole = Role::where('slug', 'author')->first();
        if ($authorRole) {
            $authorPermissions = Permission::whereIn('slug', [
                'dashboard.view',
                'blog.view', 'blog.create', 'blog.edit',
                'portfolio.view', 'portfolio.create', 'portfolio.edit',
            ])->pluck('id')->toArray();
            $authorRole->permissions()->sync($authorPermissions);
        }

        // Assign viewer permissions
        $viewerRole = Role::where('slug', 'viewer')->first();
        if ($viewerRole) {
            $viewerPermissions = Permission::where('slug', 'like', '%.view')->pluck('id')->toArray();
            $viewerRole->permissions()->sync($viewerPermissions);
        }

        // Assign super-admin role to the first user (if exists)
        $firstUser = User::first();
        if ($firstUser) {
            $superAdminRole = Role::where('slug', 'super-admin')->first();
            if ($superAdminRole) {
                $firstUser->roles()->syncWithoutDetaching($superAdminRole->id);
            }
        }

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
