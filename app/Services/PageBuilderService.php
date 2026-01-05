<?php

namespace App\Services;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionType;
use App\Models\Banner;
use App\Models\Service;
use App\Models\Portfolio;
use App\Models\Blog;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\Client;
use App\Models\Counter;
use App\Models\HowWeWorkStep;

class PageBuilderService
{
    public function getPageBySlug(string $slug): ?Page
    {
        return Page::where('slug', $slug)
            ->where('is_active', true)
            ->with(['activePageSections.sectionType', 'activePageSections.section'])
            ->first();
    }

    public function getHomepage(): ?Page
    {
        return Page::getHomepage();
    }

    /**
     * Get section data for a page section (handles both old types and new sections)
     */
    public function getPageSectionData(PageSection $pageSection): ?array
    {
        // Handle old section types (banner, services, portfolio, etc.)
        if ($pageSection->isOldType()) {
            return [
                'type' => 'old',
                'pageSection' => $pageSection,
                'sectionType' => $pageSection->sectionType,
                'data' => $this->getSectionData($pageSection),
                'view' => 'sections.' . $pageSection->sectionType->slug,
            ];
        }

        // Handle new dynamic sections
        if ($pageSection->isNewSection()) {
            $section = $pageSection->getSectionWithOverrides();
            return [
                'type' => 'new',
                'pageSection' => $pageSection,
                'section' => $section,
                'data' => [],
                'view' => 'components.section-renderer',
            ];
        }

        return null;
    }

    public function getSectionData(PageSection $section): array
    {
        $type = $section->sectionType->slug ?? '';
        $content = $section->content ?? [];

        // Merge title from page section if set
        if ($section->title) {
            $content['title'] = $section->title;
        }

        return match ($type) {
            'banner' => $this->getBannerData($content),
            'about' => $content,
            'services' => $this->getServicesData($content),
            'how-we-work' => $this->getHowWeWorkData($content),
            'counters' => $this->getCountersData($content),
            'portfolio' => $this->getPortfolioData($content),
            'blog' => $this->getBlogData($content),
            'team' => $this->getTeamData($content),
            'testimonials' => $this->getTestimonialsData($content),
            'clients' => $this->getClientsData($content),
            'contact' => $this->getContactData(),
            'cta' => $content,
            'custom-html' => $content,
            default => $content,
        };
    }

    protected function getBannerData(array $content): array
    {
        $banners = Banner::active()->orderBy('order')->get();
        return array_merge($content, ['banners' => $banners]);
    }

    protected function getServicesData(array $content): array
    {
        $limit = $content['limit'] ?? 6;
        $services = Service::active()
            ->orderBy('order')
            ->limit($limit)
            ->get();
        return array_merge($content, ['services' => $services]);
    }

    protected function getHowWeWorkData(array $content = []): array
    {
        $steps = HowWeWorkStep::active()->orderBy('order')->get();
        return array_merge($content, ['steps' => $steps]);
    }

    protected function getCountersData(array $content = []): array
    {
        $counters = Counter::active()->orderBy('order')->get();
        return array_merge($content, ['counters' => $counters]);
    }

    protected function getPortfolioData(array $content): array
    {
        $limit = $content['limit'] ?? 6;
        $portfolios = Portfolio::active()
            ->with('category')
            ->orderBy('order')
            ->limit($limit)
            ->get();
        return array_merge($content, ['portfolios' => $portfolios]);
    }

    protected function getBlogData(array $content): array
    {
        $limit = $content['limit'] ?? 3;
        $blogs = Blog::published()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
        return array_merge($content, ['blogs' => $blogs]);
    }

    protected function getTeamData(array $content): array
    {
        $limit = $content['limit'] ?? 8;
        $team = Team::active()
            ->orderBy('order')
            ->limit($limit)
            ->get();
        return array_merge($content, ['team' => $team]);
    }

    protected function getTestimonialsData(array $content = []): array
    {
        $testimonials = Testimonial::active()->orderBy('order')->get();
        return array_merge($content, ['testimonials' => $testimonials]);
    }

    protected function getClientsData(array $content = []): array
    {
        $clients = Client::active()->orderBy('order')->get();
        return array_merge($content, ['clients' => $clients]);
    }

    protected function getContactData(): array
    {
        $settingsService = app(SettingsService::class);
        return $settingsService->getContactSettings();
    }

    public function createSection(Page $page, array $data): PageSection
    {
        return $page->sections()->create($data);
    }

    public function updateSection(PageSection $section, array $data): bool
    {
        return $section->update($data);
    }

    public function reorderSections(Page $page, array $order): void
    {
        foreach ($order as $index => $sectionId) {
            PageSection::where('id', $sectionId)
                ->where('page_id', $page->id)
                ->update(['order' => $index]);
        }
    }
}
