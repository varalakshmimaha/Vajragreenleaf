<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index()
    {
        $clients = Client::orderBy('order')->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:20480',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->fileUploadService->upload($request->file('logo'), 'clients');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = Client::max('order') + 1;

        Client::create($data);

        return back()->with('success', 'Client added successfully.');
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:20480',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $this->fileUploadService->delete($client->logo);
            $data['logo'] = $this->fileUploadService->upload($request->file('logo'), 'clients');
        }

        $data['is_active'] = $request->boolean('is_active');
        $client->update($data);

        return back()->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $this->fileUploadService->delete($client->logo);
        $client->delete();

        return back()->with('success', 'Client deleted successfully.');
    }
}
