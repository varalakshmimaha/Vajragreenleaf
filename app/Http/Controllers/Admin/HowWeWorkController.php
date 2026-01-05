<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HowWeWorkStep;
use Illuminate\Http\Request;

class HowWeWorkController extends Controller
{
    public function index()
    {
        $steps = HowWeWorkStep::orderBy('order')->get();
        return view('admin.how-we-work.index', compact('steps'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = HowWeWorkStep::max('order') + 1;

        HowWeWorkStep::create($data);

        return back()->with('success', 'Step added successfully.');
    }

    public function update(Request $request, HowWeWorkStep $step)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $step->update($data);

        return back()->with('success', 'Step updated successfully.');
    }

    public function destroy(HowWeWorkStep $step)
    {
        $step->delete();
        return back()->with('success', 'Step deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:how_we_work_steps,id',
            'items.*.order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            HowWeWorkStep::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
