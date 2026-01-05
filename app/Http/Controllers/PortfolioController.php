<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;

class PortfolioController extends Controller
{
    public function index(?string $slug = null)
    {
        $categories = PortfolioCategory::active()
            ->withCount('activePortfolios')
            ->orderBy('order')
            ->get();

        $portfoliosQuery = Portfolio::active()
            ->with('category')
            ->orderBy('order');

        $activeCategory = null;
        if ($slug) {
            $category = PortfolioCategory::where('slug', $slug)->firstOrFail();
            $portfoliosQuery->where('category_id', $category->id);
            $activeCategory = $slug;
        }

        $portfolios = $portfoliosQuery->paginate(12);

        return view('frontend.portfolio.index', compact('portfolios', 'categories', 'activeCategory'));
    }

    public function show(string $slug)
    {
        $portfolio = Portfolio::where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        $relatedPortfolios = Portfolio::active()
            ->where('id', '!=', $portfolio->id)
            ->when($portfolio->category_id, function ($query) use ($portfolio) {
                $query->where('category_id', $portfolio->category_id);
            })
            ->orderBy('order')
            ->limit(3)
            ->get();

        return view('frontend.portfolio.show', compact('portfolio', 'relatedPortfolios'));
    }
}
