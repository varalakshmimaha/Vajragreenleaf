<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        protected SettingsService $settingsService
    ) {}

    public function index()
    {
        $contactSettings = $this->settingsService->getContactSettings();
        return view('frontend.contact', compact('contactSettings'));
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactSubmission::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for contacting us. We will get back to you soon!',
            ]);
        }

        return back()->with('success', 'Thank you for contacting us. We will get back to you soon!');
    }
}
