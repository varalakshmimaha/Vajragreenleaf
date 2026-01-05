<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    protected $fileUpload;

    public function __construct(FileUploadService $fileUpload)
    {
        $this->fileUpload = $fileUpload;
    }

    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $roles = Role::active()->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::active()->get();
        $permissions = Permission::getAllGrouped();

        return view('admin.users.form', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->fileUpload->upload($request->file('avatar'), 'avatars');
        }

        $user = User::create($data);

        if (!empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        if (!empty($data['permissions'])) {
            $user->permissions()->sync($data['permissions']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::active()->get();
        $permissions = Permission::getAllGrouped();
        $userRoles = $user->roles()->pluck('roles.id')->toArray();
        $userPermissions = $user->permissions()->pluck('permissions.id')->toArray();

        return view('admin.users.form', compact('user', 'roles', 'permissions', 'userRoles', 'userPermissions'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                $this->fileUpload->delete($user->avatar);
            }
            $data['avatar'] = $this->fileUpload->upload($request->file('avatar'), 'avatars');
        }

        // Remove avatar if requested
        if ($request->boolean('remove_avatar') && $user->avatar) {
            $this->fileUpload->delete($user->avatar);
            $data['avatar'] = null;
        }

        $user->update($data);
        $user->roles()->sync($data['roles'] ?? []);
        $user->permissions()->sync($data['permissions'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting last super admin
        if ($user->isSuperAdmin()) {
            $superAdminCount = User::whereHas('roles', function ($q) {
                $q->where('is_super_admin', true);
            })->count();

            if ($superAdminCount <= 1) {
                return back()->with('error', 'Cannot delete the last super admin.');
            }
        }

        if ($user->avatar) {
            $this->fileUpload->delete($user->avatar);
        }

        $user->roles()->detach();
        $user->permissions()->detach();
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        // Prevent deactivating self
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Cannot deactivate your own account.'], 403);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $user->is_active,
            'message' => $user->is_active ? 'User activated.' : 'User deactivated.',
        ]);
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        // Verify current password if changing password
        if (!empty($data['password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['current_password']);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                $this->fileUpload->delete($user->avatar);
            }
            $data['avatar'] = $this->fileUpload->upload($request->file('avatar'), 'avatars');
        }

        if ($request->boolean('remove_avatar') && $user->avatar) {
            $this->fileUpload->delete($user->avatar);
            $data['avatar'] = null;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}
