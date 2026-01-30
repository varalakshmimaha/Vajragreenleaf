<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\BankerController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\HowWeWorkController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Admin\CareerController as AdminCareerController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\CustomerController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FrontendAuthController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Services
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');
Route::post('/services/enquiry', [ServiceController::class, 'enquiry'])->name('services.enquiry');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/enquiry', [ProductController::class, 'enquiry'])->name('products.enquiry');

// Certifications
Route::get('/certifications', [CertificationController::class, 'index'])->name('certifications.index');
Route::get('/certification', [CertificationController::class, 'index']);
Route::get('/Certifications', [CertificationController::class, 'index']);

// Awards
Route::get('/awards', [AwardController::class, 'index'])->name('awards.index');
Route::get('/awards-and-rewards', [AwardController::class, 'index']);
Route::get('/Awards-and-Rewards', [AwardController::class, 'index']);
Route::get('/Awards', [AwardController::class, 'index']);

// Videos
Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
Route::get('/Videos', [VideoController::class, 'index']);

// Bankers
Route::get('/bankers', [BankerController::class, 'index'])->name('bankers.index');
Route::get('/our-bankers', [BankerController::class, 'index']);
Route::get('/Our-Bankers', [BankerController::class, 'index']);
Route::get('/Our%20Bankers', [BankerController::class, 'index']);

// Portfolio
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/category/{slug}', [PortfolioController::class, 'index'])->name('portfolio.category');
Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{slug}', [BlogController::class, 'index'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Careers
Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{slug}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/{slug}/apply', [CareerController::class, 'apply'])->name('careers.apply');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/category/{slug}', [GalleryController::class, 'index'])->name('gallery.category');

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/general', [SettingsController::class, 'general'])->name('general');
        Route::post('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
        Route::get('/seo', [SettingsController::class, 'seo'])->name('seo');
        Route::post('/seo', [SettingsController::class, 'updateSeo'])->name('seo.update');
        Route::get('/social', [SettingsController::class, 'social'])->name('social');
        Route::post('/social', [SettingsController::class, 'updateSocial'])->name('social.update');
        Route::get('/contact', [SettingsController::class, 'contact'])->name('contact');
        Route::post('/contact', [SettingsController::class, 'updateContact'])->name('contact.update');
        Route::get('/scripts', [SettingsController::class, 'scripts'])->name('scripts');
        Route::post('/scripts', [SettingsController::class, 'updateScripts'])->name('scripts.update');
    });

    // Menus
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
    Route::get('/menus/{menu}/items', [MenuController::class, 'items'])->name('menus.items');
    Route::post('/menus/{menu}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
    Route::put('/menus/items/{menuItem}', [MenuController::class, 'updateItem'])->name('menus.items.update');
    Route::delete('/menus/{menu}/items/{menuItem}', [MenuController::class, 'destroyItem'])->name('menus.items.destroy');
    Route::post('/menus/{menu}/reorder', [MenuController::class, 'reorder'])->name('menus.reorder');

    // Pages
    Route::resource('pages', AdminPageController::class);

    // Services
    Route::resource('services', AdminServiceController::class);
    Route::post('/services/{service}/plans', [AdminServiceController::class, 'storePlan'])->name('services.plans.store');
    Route::put('/service-plans/{plan}', [AdminServiceController::class, 'updatePlan'])->name('services.plans.update');
    Route::delete('/service-plans/{plan}', [AdminServiceController::class, 'destroyPlan'])->name('services.plans.destroy');

    // Products
    Route::resource('products', AdminProductController::class);
    Route::delete('/products/{product}/gallery', [AdminProductController::class, 'deleteGalleryImage'])->name('products.gallery.delete');

    // Portfolio
    Route::resource('portfolios', AdminPortfolioController::class);
    Route::get('/portfolio-categories', [AdminPortfolioController::class, 'categories'])->name('portfolios.categories');
    Route::post('/portfolio-categories', [AdminPortfolioController::class, 'storeCategory'])->name('portfolios.categories.store');
    Route::put('/portfolio-categories/{category}', [AdminPortfolioController::class, 'updateCategory'])->name('portfolios.categories.update');
    Route::delete('/portfolio-categories/{category}', [AdminPortfolioController::class, 'destroyCategory'])->name('portfolios.categories.destroy');

    // Blogs
    Route::resource('blogs', AdminBlogController::class);
    Route::get('/blog-categories', [AdminBlogController::class, 'categories'])->name('blogs.categories');
    Route::post('/blog-categories', [AdminBlogController::class, 'storeCategory'])->name('blogs.categories.store');
    Route::put('/blog-categories/{category}', [AdminBlogController::class, 'updateCategory'])->name('blogs.categories.update');
    Route::delete('/blog-categories/{category}', [AdminBlogController::class, 'destroyCategory'])->name('blogs.categories.destroy');

    // Team
    Route::resource('team', TeamController::class);
    Route::post('/team/reorder', [TeamController::class, 'reorder'])->name('team.reorder');

    // Testimonials
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::put('/testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // Clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // Banners
    Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
    Route::post('/banners', [BannerController::class, 'store'])->name('banners.store');
    Route::put('/banners/{banner}', [BannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');
    Route::post('/banners/reorder', [BannerController::class, 'reorder'])->name('banners.reorder');

    // Counters
    Route::get('/counters', [CounterController::class, 'index'])->name('counters.index');
    Route::post('/counters', [CounterController::class, 'store'])->name('counters.store');
    Route::put('/counters/{counter}', [CounterController::class, 'update'])->name('counters.update');
    Route::delete('/counters/{counter}', [CounterController::class, 'destroy'])->name('counters.destroy');

    // How We Work
    Route::get('/how-we-work', [HowWeWorkController::class, 'index'])->name('how-we-work.index');
    Route::post('/how-we-work', [HowWeWorkController::class, 'store'])->name('how-we-work.store');
    Route::put('/how-we-work/{step}', [HowWeWorkController::class, 'update'])->name('how-we-work.update');
    Route::delete('/how-we-work/{step}', [HowWeWorkController::class, 'destroy'])->name('how-we-work.destroy');
    Route::post('/how-we-work/reorder', [HowWeWorkController::class, 'reorder'])->name('how-we-work.reorder');

    // Themes
    Route::resource('themes', ThemeController::class);
    Route::post('/themes/{theme}/activate', [ThemeController::class, 'activate'])->name('themes.activate');
    Route::get('/themes/{theme}/preview', [ThemeController::class, 'preview'])->name('themes.preview');

    // Enquiries
    Route::get('/enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('/enquiries/{type}/{id}', [EnquiryController::class, 'show'])->name('enquiries.show');
    Route::post('/enquiries/{type}/{id}/status', [EnquiryController::class, 'updateStatus'])->name('enquiries.status');
    Route::delete('/enquiries/{type}/{id}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');

    // Careers
    Route::resource('careers', AdminCareerController::class);
    Route::get('/careers-applications', [AdminCareerController::class, 'applications'])->name('careers.applications');
    Route::get('/careers/applications/{application}/show', [AdminCareerController::class, 'showApplication'])->name('careers.applications.show');
    Route::post('/careers/applications/{application}/status', [AdminCareerController::class, 'updateApplicationStatus'])->name('careers.applications.status');
    Route::delete('/careers/applications/{application}', [AdminCareerController::class, 'destroyApplication'])->name('careers.applications.destroy');

    // Gallery
    Route::resource('gallery', AdminGalleryController::class);
    Route::get('/gallery-categories', [AdminGalleryController::class, 'categories'])->name('gallery.categories');
    Route::post('/gallery-categories', [AdminGalleryController::class, 'storeCategory'])->name('gallery.categories.store');
    Route::put('/gallery-categories/{category}', [AdminGalleryController::class, 'updateCategory'])->name('gallery.categories.update');
    Route::delete('/gallery-categories/{category}', [AdminGalleryController::class, 'destroyCategory'])->name('gallery.categories.destroy');

    // Certifications
    Route::post('certifications/bulk-action', [\App\Http\Controllers\Admin\CertificationController::class, 'bulkAction'])->name('certifications.bulk');
    Route::resource('certifications', \App\Http\Controllers\Admin\CertificationController::class);

    // Awards
    Route::post('awards/bulk-action', [\App\Http\Controllers\Admin\AwardController::class, 'bulkAction'])->name('awards.bulk');
    Route::resource('awards', \App\Http\Controllers\Admin\AwardController::class);

    // Videos
    Route::post('videos/bulk-action', [\App\Http\Controllers\Admin\VideoController::class, 'bulkAction'])->name('videos.bulk');
    Route::resource('videos', \App\Http\Controllers\Admin\VideoController::class);

    // Bankers
    Route::post('bankers/bulk-action', [\App\Http\Controllers\Admin\BankerController::class, 'bulkAction'])->name('bankers.bulk');
    Route::resource('bankers', \App\Http\Controllers\Admin\BankerController::class);

    // Sections
    Route::resource('sections', SectionController::class);
    Route::post('/sections/{section}/duplicate', [SectionController::class, 'duplicate'])->name('sections.duplicate');
    Route::get('/sections/{section}/preview', [SectionController::class, 'preview'])->name('sections.preview');

    // Page Builder (Section Management for Pages)
    Route::get('/pages/{page}/builder', [SectionController::class, 'pageBuilder'])->name('pages.builder');
    Route::post('/pages/{page}/add-section', [SectionController::class, 'addToPage'])->name('sections.add-to-page');
    Route::post('/pages/{page}/add-section-type', [SectionController::class, 'addSectionType'])->name('sections.add-section-type');
    Route::delete('/pages/{page}/sections/{pageSection}', [SectionController::class, 'removeFromPage'])->name('sections.remove-from-page');
    Route::post('/sections/page-section/{pageSection}/toggle', [SectionController::class, 'togglePageSection'])->name('sections.toggle-page-section');
    Route::post('/pages/{page}/sections/reorder', [SectionController::class, 'reorderPageSections'])->name('sections.reorder-page-sections');

    // Page Section API routes
    Route::get('/page-sections/{pageSection}', [SectionController::class, 'getPageSection'])->name('page-sections.show');
    Route::put('/page-sections/{pageSection}', [SectionController::class, 'updatePageSection'])->name('page-sections.update');

    // Customers (Website Users)
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('/customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::get('/customers/{customer}/referral-tree', [CustomerController::class, 'getReferralTree'])->name('customers.referral-tree');

    // Users (Admin Users), Roles & Permissions Management
    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('/users/{user}/referral-tree', [AdminUserController::class, 'getReferralTree'])->name('users.referral-tree');
    Route::get('/users/{user}/referral-stats', [AdminUserController::class, 'getReferralStats'])->name('users.referral-stats');
    Route::get('/profile', [AdminUserController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminUserController::class, 'updateProfile'])->name('profile.update');

    Route::resource('roles', RoleController::class);

    Route::resource('permissions', PermissionController::class);
    Route::get('/permissions-seed', [PermissionController::class, 'seedDefaults'])->name('permissions.seed');

    // Reports & References Module
    Route::prefix('reports')->name('reports.')->group(function () {
        // Reports Dashboard
        Route::get('/', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
        
        // Reference Summary Report
        Route::get('/summary', [\App\Http\Controllers\Admin\ReportController::class, 'summary'])->name('summary');
        Route::get('/summary/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportSummary'])->name('summary.export');
        
        // Level-Wise Reference Report
        Route::get('/level-wise', [\App\Http\Controllers\Admin\ReportController::class, 'levelWise'])->name('level-wise');
        Route::get('/level-wise/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportLevelWise'])->name('level-wise.export');
        
        // User-Wise Referral Report
        Route::get('/user-wise', [\App\Http\Controllers\Admin\ReportController::class, 'userWise'])->name('user-wise');
        Route::get('/user-wise/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportUserWise'])->name('user-wise.export');
        
        // Reference → Sub-Reference Drill-Down
        Route::get('/drill-down', [\App\Http\Controllers\Admin\ReportController::class, 'drillDown'])->name('drill-down');
        Route::get('/drill-down/{userId}', [\App\Http\Controllers\Admin\ReportController::class, 'drillDown'])->name('drill-down.user');
        
        // Dynamic Growth Report
        Route::get('/growth', [\App\Http\Controllers\Admin\ReportController::class, 'growth'])->name('growth');
        
        // Zero / Inactive Reference Report
        Route::get('/inactive', [\App\Http\Controllers\Admin\ReportController::class, 'inactive'])->name('inactive');
        Route::get('/inactive/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportInactive'])->name('inactive.export');
        
        // Cache Management
        Route::post('/clear-cache', [\App\Http\Controllers\Admin\ReportController::class, 'clearCache'])->name('clear-cache');
    });
});

// Frontend Auth / Registration
Route::get('/join', function() {
    return redirect()->route('auth.register');
})->name('auth.sponsor');
Route::get('/register', [FrontendAuthController::class, 'showRegister'])->name('auth.register');
Route::post('/register', [FrontendAuthController::class, 'register'])->name('auth.register.submit');
Route::get('/register/success', [FrontendAuthController::class, 'success'])->name('auth.success');
Route::get('/login', [FrontendAuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [FrontendAuthController::class, 'login'])->name('auth.login.submit');
Route::get('/password/forgot', [FrontendAuthController::class, 'showForgot'])->name('auth.forgot');
Route::post('/password/verify', [FrontendAuthController::class, 'verifyUser'])->name('auth.forgot.verify');
Route::post('/password/forgot', [FrontendAuthController::class, 'sendReset'])->name('auth.forgot.submit');
Route::post('/logout', [FrontendAuthController::class, 'logout'])->name('logout');

// User Dashboard (Authenticated)
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\UserDashboardController::class, 'index'])->name('dashboard');
    Route::put('/profile', [\App\Http\Controllers\UserDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/sponsor', [\App\Http\Controllers\UserDashboardController::class, 'updateSponsor'])->name('sponsor.update');
    Route::put('/password', [\App\Http\Controllers\UserDashboardController::class, 'updatePassword'])->name('password.update');
    
    // Referral System Routes
    Route::get('/referrals', [\App\Http\Controllers\ReferralController::class, 'dashboard'])->name('referrals.dashboard');
    Route::get('/referrals/tree', [\App\Http\Controllers\ReferralController::class, 'getReferralTree'])->name('referrals.tree');
    Route::get('/referrals/stats', [\App\Http\Controllers\ReferralController::class, 'getReferralStats'])->name('referrals.stats');
});

// Dynamic Page Route (must be last)
Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
