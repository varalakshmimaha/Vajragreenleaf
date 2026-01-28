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
        $settings = $section->settings ?? [];

        // Merge content and settings (settings takes priority)
        $data = array_merge($content, $settings);

        // Merge title from page section if set
        if ($section->title) {
            $data['title'] = $section->title;
        }

        return match ($type) {
            'banner' => $this->getBannerData($data),
            'about' => $data,
            'services' => $this->getServicesData($data),
            'how-we-work' => $this->getHowWeWorkData($data),
            'counters' => $this->getCountersData($data),
            'portfolio' => $this->getPortfolioData($data),
            'blog' => $this->getBlogData($data),
            'team' => $this->getTeamData($data),
            'testimonials' => $this->getTestimonialsData($data),
            'clients' => $this->getClientsData($data),
            'contact' => $this->getContactData(),
            'cta' => $data,
            'custom-html' => $data,
            default => $data,
        };
    }

    protected function getBannerData(array $data): array
    {
        $banners = Banner::active()->orderBy('order')->get();
        return array_merge($data, ['banners' => $banners]);
    }

    protected function getServicesData(array $data): array
    {
        $limit = $data['limit'] ?? 6;
        $services = Service::active()
            ->orderBy('order')
            ->limit($limit)
            ->get();
        return array_merge($data, ['services' => $services]);
    }

    protected function getHowWeWorkData(array $data = []): array
    {
        $steps = HowWeWorkStep::active()->orderBy('order')->get();
        return array_merge($data, ['steps' => $steps]);
    }

    protected function getCountersData(array $data = []): array
    {
        $counters = Counter::active()->orderBy('order')->get();
        return array_merge($data, ['counters' => $counters]);
    }

    protected function getPortfolioData(array $data): array
    {
        $limit = $data['limit'] ?? 6;
        $portfolios = Portfolio::active()
            ->with('category')
            ->orderBy('order')
            ->limit($limit)
            ->get();
        return array_merge($data, ['portfolios' => $portfolios]);
    }

    protected function getBlogData(array $data): array
    {
        $limit = $data['limit'] ?? 3;
        $blogs = Blog::published()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
        return array_merge($data, ['blogs' => $blogs]);
    }

    protected function getTeamData(array $data): array
    {
        $limit = $data['limit'] ?? 8;
        $team = Team::active()
            ->orderBy('order')
            ->limit($limit)
            ->get();
        return array_merge($data, ['team' => $team]);
    }

    protected function getTestimonialsData(array $data = []): array
    {
        $testimonials = Testimonial::active()->orderBy('order')->get();
        return array_merge($data, ['testimonials' => $testimonials]);
    }

    protected function getClientsData(array $data = []): array
    {
        $clients = Client::active()->orderBy('order')->get();
        return array_merge($data, ['clients' => $clients]);
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
