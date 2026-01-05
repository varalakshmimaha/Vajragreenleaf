<?php

namespace App\View\Composers;

use App\Models\Menu;
use App\Models\Theme;
use App\Services\SettingsService;
use Illuminate\View\View;

class GlobalDataComposer
{
    public function __construct(
        protected SettingsService $settingsService
    ) {}

    public function compose(View $view): void
    {
        $view->with([
            'headerMenu' => Menu::getByLocation('header'),
            'footerCol1' => Menu::getByLocation('footer_col1'),
            'footerCol2' => Menu::getByLocation('footer_col2'),
            'footerCol3' => Menu::getByLocation('footer_col3'),
            'siteSettings' => [
                'site_title' => $this->settingsService->get('site_title', 'IT Business'),
                'site_tagline' => $this->settingsService->get('site_tagline'),
                'logo' => $this->settingsService->get('logo'),
                'logo_light' => $this->settingsService->get('logo_light'),
                'favicon' => $this->settingsService->get('favicon'),
                'footer_text' => $this->settingsService->get('footer_text'),
            ],
            'seoSettings' => $this->settingsService->getSeoSettings(),
            'socialLinks' => $this->settingsService->getSocialLinks(),
            'contactSettings' => $this->settingsService->getContactSettings(),
            'scripts' => $this->settingsService->getScripts(),
            'activeTheme' => Theme::getActive(),
        ]);
    }
}
