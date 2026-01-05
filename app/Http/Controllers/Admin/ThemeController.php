<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = Theme::all();
        $activeTheme = Theme::getActive();
        return view('admin.themes.index', compact('themes', 'activeTheme'));
    }

    public function create()
    {
        return view('admin.themes.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'primary_color' => 'required|string|max:20',
            'secondary_color' => 'required|string|max:20',
            'accent_color' => 'required|string|max:20',
            'text_color' => 'required|string|max:20',
            'heading_color' => 'required|string|max:20',
            'background_color' => 'required|string|max:20',
            'heading_font' => 'required|string|max:50',
            'body_font' => 'required|string|max:50',
        ]);

        // Store in JSON format for compatibility
        $themeData = [
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'colors' => [
                'primary' => $data['primary_color'],
                'secondary' => $data['secondary_color'],
                'accent' => $data['accent_color'],
                'text' => $data['text_color'],
                'heading' => $data['heading_color'],
                'background' => $data['background_color'],
            ],
            'typography' => [
                'heading' => $data['heading_font'],
                'body' => $data['body_font'],
            ],
            'is_active' => $request->boolean('is_active'),
        ];

        if ($themeData['is_active']) {
            Theme::query()->update(['is_active' => false]);
        }

        Theme::create($themeData);

        return redirect()->route('admin.themes.index')
            ->with('success', 'Theme created successfully.');
    }

    public function edit(Theme $theme)
    {
        return view('admin.themes.form', compact('theme'));
    }

    public function update(Request $request, Theme $theme)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'primary_color' => 'required|string|max:20',
            'secondary_color' => 'required|string|max:20',
            'accent_color' => 'required|string|max:20',
            'text_color' => 'required|string|max:20',
            'heading_color' => 'required|string|max:20',
            'background_color' => 'required|string|max:20',
            'heading_font' => 'required|string|max:50',
            'body_font' => 'required|string|max:50',
        ]);

        $isActive = $request->boolean('is_active');

        if ($isActive && !$theme->is_active) {
            Theme::query()->update(['is_active' => false]);
        }

        // Update in JSON format for compatibility
        $theme->update([
            'name' => $data['name'],
            'colors' => [
                'primary' => $data['primary_color'],
                'secondary' => $data['secondary_color'],
                'accent' => $data['accent_color'],
                'text' => $data['text_color'],
                'heading' => $data['heading_color'],
                'background' => $data['background_color'],
            ],
            'typography' => [
                'heading' => $data['heading_font'],
                'body' => $data['body_font'],
            ],
            'is_active' => $isActive,
        ]);

        return back()->with('success', 'Theme updated successfully.');
    }

    public function destroy(Theme $theme)
    {
        if ($theme->is_default) {
            return back()->with('error', 'Cannot delete default theme.');
        }

        if ($theme->is_active) {
            $defaultTheme = Theme::where('is_default', true)->first();
            if ($defaultTheme) {
                $defaultTheme->activate();
            }
        }

        $theme->delete();

        return redirect()->route('admin.themes.index')
            ->with('success', 'Theme deleted successfully.');
    }

    public function activate(Theme $theme)
    {
        $theme->activate();
        return back()->with('success', 'Theme activated successfully.');
    }

    public function preview(Theme $theme)
    {
        return view('admin.themes.preview', compact('theme'));
    }
}
