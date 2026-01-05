<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::withCount('roles');

        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%');
            });
        }

        $permissions = $query->orderBy('group')->orderBy('name')->paginate(30);
        $groups = Permission::getGroups();

        return view('admin.permissions.index', compact('permissions', 'groups'));
    }

    public function create()
    {
        $groups = Permission::getGroups();
        return view('admin.permissions.form', compact('groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:permissions,slug',
            'group' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name'], '.');
        $data['is_active'] = $request->boolean('is_active', true);

        Permission::create($data);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        $groups = Permission::getGroups();
        return view('admin.permissions.form', compact('permission', 'groups'));
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:permissions,slug,' . $permission->id,
            'group' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name'], '.');
        $data['is_active'] = $request->boolean('is_active', true);

        $permission->update($data);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        // Detach from all roles and users
        $permission->roles()->detach();
        $permission->users()->detach();
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    /**
     * Seed default permissions
     */
    public function seedDefaults()
    {
        $defaults = Permission::getDefaultPermissions();
        $created = 0;

        foreach ($defaults as $group => $permissions) {
            foreach ($permissions as $perm) {
                $exists = Permission::where('slug', $perm['slug'])->exists();
                if (!$exists) {
                    Permission::create([
                        'name' => $perm['name'],
                        'slug' => $perm['slug'],
                        'group' => $group,
                        'description' => $perm['description'],
                        'is_active' => true,
                    ]);
                    $created++;
                }
            }
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', "Created {$created} new permissions.");
    }
}
