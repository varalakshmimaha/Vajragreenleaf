<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceEnquiry;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::active()
            ->orderBy('order')
            ->get();

        return view('frontend.services.index', compact('services'));
    }

    public function show(string $slug)
    {
        $service = Service::where('slug', $slug)
            ->where('is_active', true)
            ->with('activePlans')
            ->firstOrFail();

        $relatedServices = Service::active()
            ->where('id', '!=', $service->id)
            ->orderBy('order')
            ->limit(3)
            ->get();

        return view('frontend.services.show', compact('service', 'relatedServices'));
    }

    public function enquiry(Request $request)
    {
        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'service_plan_id' => 'nullable|exists:service_plans,id',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        ServiceEnquiry::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your enquiry. We will contact you soon!',
            ]);
        }

        return back()->with('success', 'Thank you for your enquiry. We will contact you soon!');
    }
}
