<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductEnquiry;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::active()
            ->orderBy('order')
            ->paginate(12);

        return view('frontend.products.index', compact('products'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::active()
            ->where('id', '!=', $product->id)
            ->orderBy('order')
            ->limit(3)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }

    public function enquiry(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        ProductEnquiry::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your interest. We will contact you soon!',
            ]);
        }

        return back()->with('success', 'Thank you for your interest. We will contact you soon!');
    }
}
