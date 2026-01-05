<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\PageBuilderService;

class HomeController extends Controller
{
    public function __construct(
        protected PageBuilderService $pageBuilderService
    ) {}

    public function index()
    {
        $page = $this->pageBuilderService->getHomepage();

        if (!$page) {
            abort(404, 'Homepage not configured');
        }

        // Get all active page sections (both old types and new sections)
        $sections = $page->activePageSections->map(function ($pageSection) {
            return $this->pageBuilderService->getPageSectionData($pageSection);
        })->filter(); // Remove any null values

        return view('frontend.home', compact('page', 'sections'));
    }
}
