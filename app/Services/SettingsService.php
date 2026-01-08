<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public function get(string $key, $default = null)
    {
        return SiteSetting::get($key, $default);
    }

    public function set(string $key, $value): void
    {
        SiteSetting::set($key, $value);
    }

    public function getGroup(string $group): array
    {
        return SiteSetting::getGroup($group);
    }

    public function getSeoSettings(): array
    {
        return [
            'site_title' => $this->get('site_title', 'IT Business'),
            'site_description' => $this->get('site_description'),
            'site_keywords' => $this->get('site_keywords'),
            'og_image' => $this->get('og_image'),
        ];
    }

    public function getContactSettings(): array
    {
        return [
            'address' => $this->get('contact_address'),
            'phone' => $this->get('contact_phone'),
            'email' => $this->get('contact_email'),
            'working_hours' => $this->get('business_hours'),
            'map_embed' => $this->get('google_maps_url'),
        ];
    }

    public function getSocialLinks(): array
    {
        return [
            'facebook' => $this->get('social_facebook'),
            'twitter' => $this->get('social_twitter'),
            'linkedin' => $this->get('social_linkedin'),
            'instagram' => $this->get('social_instagram'),
            'youtube' => $this->get('social_youtube'),
            'github' => $this->get('social_github'),
        ];
    }

    public function getScripts(): array
    {
        return [
            'head_scripts' => $this->get('head_scripts'),
            'body_start_scripts' => $this->get('body_start_scripts'),
            'body_end_scripts' => $this->get('body_end_scripts'),
        ];
    }

    public function updateSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }
    }
}
