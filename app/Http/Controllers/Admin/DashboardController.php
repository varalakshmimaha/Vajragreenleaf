<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ContactSubmission;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\ProductEnquiry;
use App\Models\Service;
use App\Models\ServiceEnquiry;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'services' => Service::count(),
            'products' => Product::count(),
            'blogs' => Blog::count(),
            'contact_submissions' => ContactSubmission::new()->count(),
        ];

        $recentContacts = ContactSubmission::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentContacts'));
    }
}
