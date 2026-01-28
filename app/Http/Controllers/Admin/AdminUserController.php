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
        // Increase execution time for large datasets
        set_time_limit(120);

        // Only show admin users (not regular 'user' role)
        $query = User::where(function ($q) {
            $q->where('role', 'admin')
              ->orWhere('role', 'super-admin')
              ->orWhereHas('roles', function ($rq) {
                  $rq->whereIn('slug', ['admin', 'super-admin']);
              });
        });

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%')
                  ->orWhere('referral_id', 'like', '%' . $request->search . '%')
                  ->orWhere('sponsor_id', 'like', '%' . $request->search . '%')
                  ->orWhere('sponsor_referral_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Eager load relationships to avoid N+1 queries
        // Only select necessary columns for better performance
        $users = $query
            ->with(['sponsorByReferralId:id,name,referral_id', 'roles'])
            ->select('id', 'name', 'email', 'username', 'referral_id', 'sponsor_id', 'sponsor_referral_id', 'role', 'is_active', 'last_login_at', 'created_at', 'avatar')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // If AJAX/json requested, return a lightweight JSON payload for polling
        if ($request->query('ajax') || $request->wantsJson()) {
            $data = $users->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'username' => $u->username,
                    'referral_id' => $u->referral_id,
                    'sponsor_id' => $u->sponsor_id,
                    'sponsor_referral_id' => $u->sponsor_referral_id,
                    'sponsor_name' => $u->sponsorByReferralId?->name ?? null,
                    'role' => $u->role,
                    'is_active' => (bool) $u->is_active,
                    'last_login_at' => $u->last_login_at ? $u->last_login_at->toDateTimeString() : null,
                ];
            });

            return response()->json([
                'data' => $data,
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ]);
        }

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::active()->get();
        $permissions = Permission::getAllGrouped();

        return view('admin.users.form', compact('roles', 'permissions'));
    }

    public function show(User $user)
    {
        $user->load('sponsor', 'children', 'sponsorByReferralId', 'referrals');
        
        // Get referral statistics
        $stats = [
            'level1' => $user->referrals()->count(),
            'level2' => 0,
            'level3' => 0,
        ];
        
        foreach ($user->referrals as $level1) {
            $stats['level2'] += $level1->referrals()->count();
            foreach ($level1->referrals as $level2) {
                $stats['level3'] += $level2->referrals()->count();
            }
        }
        
        $stats['total'] = $stats['level1'] + $stats['level2'] + $stats['level3'];
        
        return view('admin.users.show', compact('user', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:20480',
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
            'avatar' => 'nullable|image|max:20480',
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
            'avatar' => 'nullable|image|max:20480',
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

    /**
     * Get referral tree for a specific user (API endpoint)
     */
    public function getReferralTree(Request $request, User $user)
    {
        $maxLevel = $request->query('maxLevel', 3);
        
        $referrals = $user->getAllReferrals($maxLevel);
        $hasMore = $user->hasReferralsBeyondLevel($maxLevel);

        return response()->json([
            'referralID' => $user->referral_id,
            'name' => $user->name,
            'referrals' => $referrals,
            'hasMore' => $hasMore,
            'totalDirectReferrals' => $user->referrals()->count(),
        ]);
    }

    /**
     * Get referral statistics for a user
     */
    public function getReferralStats(User $user)
    {
        $level1Count = $user->referrals()->count();
        $level2Count = 0;
        $level3Count = 0;
        
        foreach ($user->referrals as $level1) {
            $level2Count += $level1->referrals()->count();
            foreach ($level1->referrals as $level2) {
                $level3Count += $level2->referrals()->count();
            }
        }

        $totalReferrals = $level1Count + $level2Count + $level3Count;

        return response()->json([
            'referralID' => $user->referral_id,
            'totalReferrals' => $totalReferrals,
            'level1' => $level1Count,
            'level2' => $level2Count,
            'level3' => $level3Count,
        ]);
    }
}
