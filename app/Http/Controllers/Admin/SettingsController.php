<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\FileUploadService;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingsService $settingsService,
        protected FileUploadService $fileUploadService
    ) {}

    public function general()
    {
        $settings = SiteSetting::where('group', 'general')->orderBy('order')->get();
        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $data = $request->validate([
            'site_title' => 'nullable|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_light' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico|max:1024',
            'footer_text' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $this->fileUploadService->delete($this->settingsService->get('logo'));
            $data['logo'] = $this->fileUploadService->upload($request->file('logo'), 'settings');
        }

        if ($request->hasFile('logo_light')) {
            $this->fileUploadService->delete($this->settingsService->get('logo_light'));
            $data['logo_light'] = $this->fileUploadService->upload($request->file('logo_light'), 'settings');
        }

        if ($request->hasFile('favicon')) {
            $this->fileUploadService->delete($this->settingsService->get('favicon'));
            $data['favicon'] = $this->fileUploadService->upload($request->file('favicon'), 'settings');
        }

        foreach ($data as $key => $value) {
            if (!$request->hasFile($key) || $value) {
                $this->settingsService->set($key, $value);
            }
        }

        return back()->with('success', 'General settings updated successfully.');
    }

    public function seo()
    {
        $settings = $this->settingsService->getGroup('seo');
        return view('admin.settings.seo', compact('settings'));
    }

    public function updateSeo(Request $request)
    {
        $data = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'google_analytics' => 'nullable|string',
            'google_tag_manager' => 'nullable|string',
            'robots' => 'nullable|string',
        ]);

        if ($request->hasFile('og_image')) {
            $this->fileUploadService->delete($this->settingsService->get('og_image'));
            $data['og_image'] = $this->fileUploadService->upload($request->file('og_image'), 'settings');
        }

        foreach ($data as $key => $value) {
            if (!$request->hasFile($key) || $value) {
                $this->settingsService->set($key, $value);
            }
        }

        return back()->with('success', 'SEO settings updated successfully.');
    }

    public function social()
    {
        $settings = $this->settingsService->getGroup('social');
        return view('admin.settings.social', compact('settings'));
    }

    public function updateSocial(Request $request)
    {
        $data = $request->validate([
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'social_github' => 'nullable|url|max:255',
        ]);

        foreach ($data as $key => $value) {
            $this->settingsService->set($key, $value);
        }

        return back()->with('success', 'Social links updated successfully.');
    }

    public function contact()
    {
        $settings = $this->settingsService->getGroup('contact');
        return view('admin.settings.contact', compact('settings'));
    }

    public function updateContact(Request $request)
    {
        $data = $request->validate([
            'contact_address' => 'nullable|string',
            'contact_phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'support_email' => 'nullable|email|max:255',
            'business_hours' => 'nullable|string|max:255',
            'google_maps_url' => 'nullable|string',
        ]);

        foreach ($data as $key => $value) {
            $this->settingsService->set($key, $value);
        }

        return back()->with('success', 'Contact settings updated successfully.');
    }

    public function scripts()
    {
        $settings = $this->settingsService->getGroup('scripts');
        return view('admin.settings.scripts', compact('settings'));
    }

    public function updateScripts(Request $request)
    {
        $data = $request->validate([
            'head_scripts' => 'nullable|string',
            'body_start_scripts' => 'nullable|string',
            'body_end_scripts' => 'nullable|string',
        ]);

        foreach ($data as $key => $value) {
            $this->settingsService->set($key, $value);
        }

        return back()->with('success', 'Scripts updated successfully.');
    }
}
