<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'mobile',
        'username',
        'sponsor_id',
        'referral_id',
        'sponsor_referral_id',
        'address',
        'state',
        'city',
        'pincode',
        'avatar',
        'is_active',
        'role',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get user's roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    /**
     * Get user's direct permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withTimestamps();
    }

    /**
     * Get all permissions (from roles + direct permissions)
     */
    public function getAllPermissions(): \Illuminate\Support\Collection
    {
        // Get permissions from roles
        $rolePermissions = $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('slug');

        // Get direct permissions
        $directPermissions = $this->permissions()->pluck('slug');

        return $rolePermissions->merge($directPermissions)->unique();
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->roles()->where('is_super_admin', true)->exists();
    }

    /**
     * Check if user is admin (has any admin role)
     */
    public function isAdmin(): bool
    {
        // Respect legacy `role` column (string) for backwards compatibility
        $roleCol = strtolower($this->role ?? '');
        if (in_array($roleCol, ['super-admin', 'admin'], true)) {
            return true;
        }

        return $this->roles()->whereIn('slug', ['super-admin', 'admin'])->exists()
            || $this->isSuperAdmin();
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Check role permissions
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        // Check direct permissions
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Assign role to user
     */
    public function assignRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->first();
        }

        if ($role) {
            $this->roles()->syncWithoutDetaching($role->id);
        }
    }

    /**
     * Remove role from user
     */
    public function removeRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Sync user roles
     */
    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }

    /**
     * Give direct permission to user
     */
    public function givePermission($permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->syncWithoutDetaching($permission->id);
        }
    }

    /**
     * Revoke direct permission from user
     */
    public function revokePermission($permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }

    /**
     * Sync user direct permissions
     */
    public function syncPermissions(array $permissionIds): void
    {
        $this->permissions()->sync($permissionIds);
    }

    /**
     * Get user's primary role
     */
    public function getPrimaryRole(): ?Role
    {
        return $this->roles()->orderBy('is_super_admin', 'desc')->first();
    }

    /**
     * Get user's role names
     */
    public function getRoleNames(): array
    {
        return $this->roles()->pluck('name')->toArray();
    }

    /**
     * Blogs relationship
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Return gravatar or default avatar
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=150";
    }

    /**
     * Get the sponsor (referrer) of this user
     */
    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id', 'username');
    }

    /**
     * Get the sponsor by referral ID (new system)
     */
    public function sponsorByReferralId()
    {
        return $this->belongsTo(User::class, 'sponsor_referral_id', 'referral_id');
    }

    /**
     * Get users directly referred by this user (using username - legacy)
     */
    public function children()
    {
        return $this->hasMany(User::class, 'sponsor_id', 'username');
    }

    /**
     * Get users directly referred by this user (using referral_id - new system)
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'sponsor_referral_id', 'referral_id');
    }

    /**
     * Get all referrals recursively (multilevel tree)
     */
    public function getAllReferrals($maxLevel = null, $currentLevel = 1)
    {
        $directReferrals = $this->referrals()->with(['referrals'])->get();
        
        $result = [];
        foreach ($directReferrals as $referral) {
            $referralData = [
                'referralID' => $referral->referral_id,
                'name' => $referral->name,
                'email' => $referral->email,
                'level' => $currentLevel,
                'created_at' => $referral->created_at,
                'children' => []
            ];
            
            // Recursively get children if not at max level
            if ($maxLevel === null || $currentLevel < $maxLevel) {
                $children = $referral->getAllReferrals($maxLevel, $currentLevel + 1);
                $referralData['children'] = $children;
            }
            
            $result[] = $referralData;
        }
        
        return $result;
    }

    /**
     * Check if user has referrals beyond a certain level
     */
    public function hasReferralsBeyondLevel($level)
    {
        if ($level <= 0) {
            return $this->referrals()->count() > 0;
        }
        
        $directReferrals = $this->referrals;
        foreach ($directReferrals as $referral) {
            if ($referral->hasReferralsBeyondLevel($level - 1)) {
                return true;
            }
        }
        
        return false;
    }
}
