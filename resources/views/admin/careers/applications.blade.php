@extends('layouts.admin')

@section('title', 'Job Applications')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Job Applications</h1>
            @if($career)
                <p class="text-gray-600 mt-1">For: {{ $career->title }}</p>
            @endif
        </div>
        <a href="{{ route('admin.careers.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Jobs
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter by Job -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form method="GET" class="flex items-center space-x-4">
            <label class="text-sm font-medium text-gray-700">Filter by Job:</label>
            <select name="career" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Jobs</option>
                @foreach($careers as $job)
                    <option value="{{ $job->id }}" {{ $career && $career->id == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($applications as $application)
                    <tr class="{{ $application->status == 'pending' ? 'bg-yellow-50' : '' }}">
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-medium text-gray-900">{{ $application->name }}</div>
                                <div class="text-sm text-gray-500">{{ $application->email }}</div>
                                <div class="text-sm text-gray-500">{{ $application->phone }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $application->career->title }}</div>
                            @if($application->current_position && $application->current_company)
                                <div class="text-xs text-gray-500">{{ $application->current_position }} at {{ $application->current_company }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $application->created_at->format('M d, Y') }}
                            <br>
                            <span class="text-xs">{{ $application->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'reviewing' => 'bg-blue-100 text-blue-800',
                                    'shortlisted' => 'bg-purple-100 text-purple-800',
                                    'interviewed' => 'bg-indigo-100 text-indigo-800',
                                    'offered' => 'bg-cyan-100 text-cyan-800',
                                    'hired' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }} rounded-full">
                                {{ $application->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="viewApplication({{ $application->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($application->resume)
                                <a href="{{ asset('storage/' . $application->resume) }}" target="_blank" class="text-green-600 hover:text-green-900 mr-3" title="Download Resume">
                                    <i class="fas fa-file-download"></i>
                                </a>
                            @endif
                            <form action="{{ route('admin.careers.applications.destroy', $application) }}" method="POST" class="inline" onsubmit="return confirm('Delete this application?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No applications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($applications->hasPages())
        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    @endif

    <!-- View Application Modal -->
    <div id="application-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-semibold">Application Details</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="modal-content">
                    <div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function viewApplication(id) {
        document.getElementById('application-modal').classList.remove('hidden');
        document.getElementById('modal-content').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';

        fetch(`/admin/careers/applications/${id}/show`)
            .then(response => response.json())
            .then(data => {
                let html = `
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-500">Name</label>
                                <p class="font-medium">${data.name}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Email</label>
                                <p class="font-medium">${data.email}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Phone</label>
                                <p class="font-medium">${data.phone}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Position Applied</label>
                                <p class="font-medium">${data.career?.title || 'N/A'}</p>
                            </div>
                        </div>

                        ${data.current_company || data.current_position ? `
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-500">Current Company</label>
                                <p class="font-medium">${data.current_company || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Current Position</label>
                                <p class="font-medium">${data.current_position || 'N/A'}</p>
                            </div>
                        </div>
                        ` : ''}

                        <div class="grid grid-cols-2 gap-4">
                            ${data.expected_salary ? `<div><label class="text-sm text-gray-500">Expected Salary</label><p class="font-medium">${data.expected_salary}</p></div>` : ''}
                            ${data.available_from ? `<div><label class="text-sm text-gray-500">Available From</label><p class="font-medium">${data.available_from}</p></div>` : ''}
                        </div>

                        <div class="flex gap-4">
                            ${data.linkedin_url ? `<a href="${data.linkedin_url}" target="_blank" class="text-blue-600 hover:underline"><i class="fab fa-linkedin mr-1"></i>LinkedIn</a>` : ''}
                            ${data.portfolio_url ? `<a href="${data.portfolio_url}" target="_blank" class="text-blue-600 hover:underline"><i class="fas fa-globe mr-1"></i>Portfolio</a>` : ''}
                            ${data.resume ? `<a href="/storage/${data.resume}" target="_blank" class="text-green-600 hover:underline"><i class="fas fa-file-pdf mr-1"></i>Resume</a>` : ''}
                        </div>

                        ${data.cover_letter ? `
                        <div>
                            <label class="text-sm text-gray-500">Cover Letter</label>
                            <p class="mt-1 text-gray-700 whitespace-pre-wrap">${data.cover_letter}</p>
                        </div>
                        ` : ''}

                        <div class="pt-4 border-t">
                            <form action="/admin/careers/applications/${data.id}/status" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="text-sm text-gray-500">Update Status</label>
                                    <select name="status" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg">
                                        <option value="pending" ${data.status === 'pending' ? 'selected' : ''}>Pending Review</option>
                                        <option value="reviewing" ${data.status === 'reviewing' ? 'selected' : ''}>Under Review</option>
                                        <option value="shortlisted" ${data.status === 'shortlisted' ? 'selected' : ''}>Shortlisted</option>
                                        <option value="interviewed" ${data.status === 'interviewed' ? 'selected' : ''}>Interviewed</option>
                                        <option value="offered" ${data.status === 'offered' ? 'selected' : ''}>Offer Made</option>
                                        <option value="hired" ${data.status === 'hired' ? 'selected' : ''}>Hired</option>
                                        <option value="rejected" ${data.status === 'rejected' ? 'selected' : ''}>Not Selected</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-500">Notes</label>
                                    <textarea name="notes" rows="3" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg">${data.notes || ''}</textarea>
                                </div>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Update Status</button>
                            </form>
                        </div>
                    </div>
                `;
                document.getElementById('modal-content').innerHTML = html;
            });
    }

    function closeModal() {
        document.getElementById('application-modal').classList.add('hidden');
    }

    document.getElementById('application-modal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endpush
