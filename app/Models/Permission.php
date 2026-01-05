<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'group',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permissions')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get all permission groups
     */
    public static function getGroups(): array
    {
        return static::distinct()->pluck('group')->filter()->toArray();
    }

    /**
     * Get permissions grouped by their group
     */
    public static function getAllGrouped()
    {
        return static::active()
            ->orderBy('group')
            ->orderBy('name')
            ->get()
            ->groupBy('group');
    }

    /**
     * Default permission groups and their permissions
     */
    public static function getDefaultPermissions(): array
    {
        return [
            'dashboard' => [
                ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'description' => 'Can view admin dashboard'],
            ],
            'users' => [
                ['name' => 'View Users', 'slug' => 'users.view', 'description' => 'Can view users list'],
                ['name' => 'Create Users', 'slug' => 'users.create', 'description' => 'Can create new users'],
                ['name' => 'Edit Users', 'slug' => 'users.edit', 'description' => 'Can edit existing users'],
                ['name' => 'Delete Users', 'slug' => 'users.delete', 'description' => 'Can delete users'],
            ],
            'roles' => [
                ['name' => 'View Roles', 'slug' => 'roles.view', 'description' => 'Can view roles list'],
                ['name' => 'Create Roles', 'slug' => 'roles.create', 'description' => 'Can create new roles'],
                ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'description' => 'Can edit existing roles'],
                ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'description' => 'Can delete roles'],
            ],
            'permissions' => [
                ['name' => 'View Permissions', 'slug' => 'permissions.view', 'description' => 'Can view permissions'],
                ['name' => 'Create Permissions', 'slug' => 'permissions.create', 'description' => 'Can create new permissions'],
                ['name' => 'Edit Permissions', 'slug' => 'permissions.edit', 'description' => 'Can edit permissions'],
                ['name' => 'Delete Permissions', 'slug' => 'permissions.delete', 'description' => 'Can delete permissions'],
            ],
            'pages' => [
                ['name' => 'View Pages', 'slug' => 'pages.view', 'description' => 'Can view pages'],
                ['name' => 'Create Pages', 'slug' => 'pages.create', 'description' => 'Can create new pages'],
                ['name' => 'Edit Pages', 'slug' => 'pages.edit', 'description' => 'Can edit existing pages'],
                ['name' => 'Delete Pages', 'slug' => 'pages.delete', 'description' => 'Can delete pages'],
                ['name' => 'Manage Page Builder', 'slug' => 'pages.builder', 'description' => 'Can use page builder'],
            ],
            'sections' => [
                ['name' => 'View Sections', 'slug' => 'sections.view', 'description' => 'Can view sections'],
                ['name' => 'Create Sections', 'slug' => 'sections.create', 'description' => 'Can create new sections'],
                ['name' => 'Edit Sections', 'slug' => 'sections.edit', 'description' => 'Can edit existing sections'],
                ['name' => 'Delete Sections', 'slug' => 'sections.delete', 'description' => 'Can delete sections'],
            ],
            'services' => [
                ['name' => 'View Services', 'slug' => 'services.view', 'description' => 'Can view services'],
                ['name' => 'Create Services', 'slug' => 'services.create', 'description' => 'Can create new services'],
                ['name' => 'Edit Services', 'slug' => 'services.edit', 'description' => 'Can edit existing services'],
                ['name' => 'Delete Services', 'slug' => 'services.delete', 'description' => 'Can delete services'],
            ],
            'products' => [
                ['name' => 'View Products', 'slug' => 'products.view', 'description' => 'Can view products'],
                ['name' => 'Create Products', 'slug' => 'products.create', 'description' => 'Can create new products'],
                ['name' => 'Edit Products', 'slug' => 'products.edit', 'description' => 'Can edit existing products'],
                ['name' => 'Delete Products', 'slug' => 'products.delete', 'description' => 'Can delete products'],
            ],
            'portfolio' => [
                ['name' => 'View Portfolio', 'slug' => 'portfolio.view', 'description' => 'Can view portfolio'],
                ['name' => 'Create Portfolio', 'slug' => 'portfolio.create', 'description' => 'Can create new portfolio items'],
                ['name' => 'Edit Portfolio', 'slug' => 'portfolio.edit', 'description' => 'Can edit existing portfolio items'],
                ['name' => 'Delete Portfolio', 'slug' => 'portfolio.delete', 'description' => 'Can delete portfolio items'],
            ],
            'blog' => [
                ['name' => 'View Blog', 'slug' => 'blog.view', 'description' => 'Can view blog posts'],
                ['name' => 'Create Blog', 'slug' => 'blog.create', 'description' => 'Can create new blog posts'],
                ['name' => 'Edit Blog', 'slug' => 'blog.edit', 'description' => 'Can edit existing blog posts'],
                ['name' => 'Delete Blog', 'slug' => 'blog.delete', 'description' => 'Can delete blog posts'],
            ],
            'team' => [
                ['name' => 'View Team', 'slug' => 'team.view', 'description' => 'Can view team members'],
                ['name' => 'Create Team', 'slug' => 'team.create', 'description' => 'Can create new team members'],
                ['name' => 'Edit Team', 'slug' => 'team.edit', 'description' => 'Can edit existing team members'],
                ['name' => 'Delete Team', 'slug' => 'team.delete', 'description' => 'Can delete team members'],
            ],
            'testimonials' => [
                ['name' => 'View Testimonials', 'slug' => 'testimonials.view', 'description' => 'Can view testimonials'],
                ['name' => 'Create Testimonials', 'slug' => 'testimonials.create', 'description' => 'Can create new testimonials'],
                ['name' => 'Edit Testimonials', 'slug' => 'testimonials.edit', 'description' => 'Can edit existing testimonials'],
                ['name' => 'Delete Testimonials', 'slug' => 'testimonials.delete', 'description' => 'Can delete testimonials'],
            ],
            'menus' => [
                ['name' => 'View Menus', 'slug' => 'menus.view', 'description' => 'Can view menus'],
                ['name' => 'Create Menus', 'slug' => 'menus.create', 'description' => 'Can create new menus'],
                ['name' => 'Edit Menus', 'slug' => 'menus.edit', 'description' => 'Can edit existing menus'],
                ['name' => 'Delete Menus', 'slug' => 'menus.delete', 'description' => 'Can delete menus'],
            ],
            'gallery' => [
                ['name' => 'View Gallery', 'slug' => 'gallery.view', 'description' => 'Can view gallery'],
                ['name' => 'Create Gallery', 'slug' => 'gallery.create', 'description' => 'Can create new gallery items'],
                ['name' => 'Edit Gallery', 'slug' => 'gallery.edit', 'description' => 'Can edit existing gallery items'],
                ['name' => 'Delete Gallery', 'slug' => 'gallery.delete', 'description' => 'Can delete gallery items'],
            ],
            'careers' => [
                ['name' => 'View Careers', 'slug' => 'careers.view', 'description' => 'Can view careers'],
                ['name' => 'Create Careers', 'slug' => 'careers.create', 'description' => 'Can create new career postings'],
                ['name' => 'Edit Careers', 'slug' => 'careers.edit', 'description' => 'Can edit existing career postings'],
                ['name' => 'Delete Careers', 'slug' => 'careers.delete', 'description' => 'Can delete career postings'],
                ['name' => 'Manage Applications', 'slug' => 'careers.applications', 'description' => 'Can manage job applications'],
            ],
            'enquiries' => [
                ['name' => 'View Enquiries', 'slug' => 'enquiries.view', 'description' => 'Can view enquiries'],
                ['name' => 'Manage Enquiries', 'slug' => 'enquiries.manage', 'description' => 'Can manage enquiries'],
                ['name' => 'Delete Enquiries', 'slug' => 'enquiries.delete', 'description' => 'Can delete enquiries'],
            ],
            'settings' => [
                ['name' => 'View Settings', 'slug' => 'settings.view', 'description' => 'Can view settings'],
                ['name' => 'Edit Settings', 'slug' => 'settings.edit', 'description' => 'Can edit settings'],
            ],
            'themes' => [
                ['name' => 'View Themes', 'slug' => 'themes.view', 'description' => 'Can view themes'],
                ['name' => 'Manage Themes', 'slug' => 'themes.manage', 'description' => 'Can manage themes'],
            ],
            'banners' => [
                ['name' => 'View Banners', 'slug' => 'banners.view', 'description' => 'Can view banners'],
                ['name' => 'Manage Banners', 'slug' => 'banners.manage', 'description' => 'Can manage banners'],
            ],
            'clients' => [
                ['name' => 'View Clients', 'slug' => 'clients.view', 'description' => 'Can view clients'],
                ['name' => 'Manage Clients', 'slug' => 'clients.manage', 'description' => 'Can manage clients'],
            ],
            'counters' => [
                ['name' => 'View Counters', 'slug' => 'counters.view', 'description' => 'Can view counters'],
                ['name' => 'Manage Counters', 'slug' => 'counters.manage', 'description' => 'Can manage counters'],
            ],
        ];
    }
}
