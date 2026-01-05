<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\ProductEnquiry;
use App\Models\ServiceEnquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'contact');

        switch ($type) {
            case 'service':
                $enquiries = ServiceEnquiry::with(['service', 'plan'])
                    ->latest()
                    ->paginate(20);
                break;
            case 'product':
                $enquiries = ProductEnquiry::with('product')
                    ->latest()
                    ->paginate(20);
                break;
            default:
                $enquiries = ContactSubmission::latest()->paginate(20);
                $type = 'contact';
        }

        return view('admin.enquiries.index', compact('enquiries', 'type'));
    }

    public function show(string $type, int $id)
    {
        switch ($type) {
            case 'service':
                $enquiry = ServiceEnquiry::with(['service', 'plan'])->findOrFail($id);
                break;
            case 'product':
                $enquiry = ProductEnquiry::with('product')->findOrFail($id);
                break;
            default:
                $enquiry = ContactSubmission::findOrFail($id);
        }

        return response()->json($enquiry);
    }

    public function updateStatus(Request $request, string $type, int $id)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,contacted,converted,closed,new,read,replied',
        ]);

        switch ($type) {
            case 'service':
                ServiceEnquiry::where('id', $id)->update(['status' => $data['status']]);
                break;
            case 'product':
                ProductEnquiry::where('id', $id)->update(['status' => $data['status']]);
                break;
            default:
                ContactSubmission::where('id', $id)->update(['status' => $data['status']]);
        }

        return back()->with('success', 'Status updated successfully.');
    }

    public function destroy(string $type, int $id)
    {
        switch ($type) {
            case 'service':
                ServiceEnquiry::destroy($id);
                break;
            case 'product':
                ProductEnquiry::destroy($id);
                break;
            default:
                ContactSubmission::destroy($id);
        }

        return back()->with('success', 'Enquiry deleted successfully.');
    }
}
