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

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'services' => Service::count(),
            'products' => Product::count(),
            'portfolios' => Portfolio::count(),
            'blogs' => Blog::count(),
            'service_enquiries' => ServiceEnquiry::pending()->count(),
            'product_enquiries' => ProductEnquiry::pending()->count(),
            'contact_submissions' => ContactSubmission::new()->count(),
        ];

        $recentEnquiries = ServiceEnquiry::with('service')
            ->latest()
            ->take(5)
            ->get();

        $recentContacts = ContactSubmission::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentEnquiries', 'recentContacts'));
    }
}
