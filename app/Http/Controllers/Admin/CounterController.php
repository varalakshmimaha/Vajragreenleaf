<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index()
    {
        $counters = Counter::orderBy('order')->get();
        return view('admin.counters.index', compact('counters'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|integer|min:0',
            'suffix' => 'nullable|string|max:10',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = Counter::max('order') + 1;

        Counter::create($data);

        return back()->with('success', 'Counter added successfully.');
    }

    public function update(Request $request, Counter $counter)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|integer|min:0',
            'suffix' => 'nullable|string|max:10',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $counter->update($data);

        return back()->with('success', 'Counter updated successfully.');
    }

    public function destroy(Counter $counter)
    {
        $counter->delete();
        return back()->with('success', 'Counter deleted successfully.');
    }
}
