<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\PageBuilderService;

class PageController extends Controller
{
    public function __construct(
        protected PageBuilderService $pageBuilderService
    ) {}

    public function show(string $slug)
    {
        $page = $this->pageBuilderService->getPageBySlug($slug);

        if (!$page) {
            abort(404);
        }

        // Get all active page sections (both old types and new sections)
        $sections = $page->activePageSections->map(function ($pageSection) {
            return $this->pageBuilderService->getPageSectionData($pageSection);
        })->filter(); // Remove any null values

        return view('frontend.page', compact('page', 'sections'));
    }
}
