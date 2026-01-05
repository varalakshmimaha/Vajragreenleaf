<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('items')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        Menu::create([
            'name' => $request->name,
            'location' => $request->location,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $menu->load(['items.children']);
        return view('admin.menus.form', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $menu->update([
            'name' => $request->name,
            'location' => $request->location,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->items()->delete();
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }

    public function items(Menu $menu)
    {
        $menu->load(['items.children']);
        return view('admin.menus.items', compact('menu'));
    }

    public function storeItem(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'target' => 'in:_self,_blank',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
        ]);

        $maxOrder = $menu->allItems()->max('order') ?? 0;
        $data['order'] = $maxOrder + 1;
        $data['is_active'] = true;

        $menu->allItems()->create($data);

        return back()->with('success', 'Menu item added successfully.');
    }

    public function updateItem(Request $request, MenuItem $menuItem)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'target' => 'in:_self,_blank',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $menuItem->update($data);

        return back()->with('success', 'Menu item updated successfully.');
    }

    public function destroyItem(Menu $menu, MenuItem $menuItem)
    {
        $menuItem->delete();
        return back()->with('success', 'Menu item deleted successfully.');
    }

    public function reorder(Request $request, Menu $menu)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.order' => 'required|integer',
            'items.*.parent_id' => 'nullable|exists:menu_items,id',
        ]);

        foreach ($request->items as $item) {
            MenuItem::where('id', $item['id'])
                ->where('menu_id', $menu->id)
                ->update([
                    'order' => $item['order'],
                    'parent_id' => $item['parent_id'],
                ]);
        }

        return response()->json(['success' => true]);
    }
}
