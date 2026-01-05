@extends('layouts.admin')

@section('title', isset($career) ? 'Edit Job Posting' : 'Create Job Posting')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ isset($career) ? 'Edit Job Posting' : 'Create Job Posting' }}</h1>
    </div>

    <form action="{{ isset($career) ? route('admin.careers.update', $career) : route('admin.careers.store') }}" method="POST">
        @csrf
        @if(isset($career))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Job Details</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Job Title *</label>
                            <input type="text" name="title" value="{{ old('title', $career->title ?? '') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <input type="text" name="department" value="{{ old('department', $career->department ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="e.g., Engineering, Marketing">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                            <input type="text" name="location" value="{{ old('location', $career->location ?? '') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="e.g., New York, NY or Remote">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Salary Range</label>
                            <input type="text" name="salary_range" value="{{ old('salary_range', $career->salary_range ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="e.g., $80,000 - $120,000">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Job Type *</label>
                            <select name="job_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="full-time" {{ old('job_type', $career->job_type ?? '') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part-time" {{ old('job_type', $career->job_type ?? '') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ old('job_type', $career->job_type ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="remote" {{ old('job_type', $career->job_type ?? '') == 'remote' ? 'selected' : '' }}>Remote</option>
                                <option value="internship" {{ old('job_type', $career->job_type ?? '') == 'internship' ? 'selected' : '' }}>Internship</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Experience Level *</label>
                            <select name="experience_level" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="entry" {{ old('experience_level', $career->experience_level ?? '') == 'entry' ? 'selected' : '' }}>Entry Level</option>
                                <option value="mid" {{ old('experience_level', $career->experience_level ?? 'mid') == 'mid' ? 'selected' : '' }}>Mid Level</option>
                                <option value="senior" {{ old('experience_level', $career->experience_level ?? '') == 'senior' ? 'selected' : '' }}>Senior Level</option>
                                <option value="lead" {{ old('experience_level', $career->experience_level ?? '') == 'lead' ? 'selected' : '' }}>Lead/Manager</option>
                                <option value="executive" {{ old('experience_level', $career->experience_level ?? '') == 'executive' ? 'selected' : '' }}>Executive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Positions Available</label>
                            <input type="number" name="positions" value="{{ old('positions', $career->positions ?? 1) }}" min="1"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                        <textarea name="short_description" rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Brief summary for job listings">{{ old('short_description', $career->short_description ?? '') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Job Description *</h2>
                    <textarea name="description" rows="10" id="description-editor" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $career->description ?? '') }}</textarea>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Requirements</h2>
                    <textarea name="requirements" rows="8" id="requirements-editor"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="List the qualifications and skills required">{{ old('requirements', $career->requirements ?? '') }}</textarea>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Benefits</h2>
                    <textarea name="benefits" rows="6" id="benefits-editor"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="List the benefits and perks">{{ old('benefits', $career->benefits ?? '') }}</textarea>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">SEO Settings</h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $career->meta_title ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea name="meta_description" rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('meta_description', $career->meta_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Publish</h2>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $career->is_active ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $career->is_featured ?? false) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Featured</span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Application Deadline</label>
                        <input type="date" name="application_deadline" value="{{ old('application_deadline', isset($career) && $career->application_deadline ? $career->application_deadline->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Leave empty for no deadline</p>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            {{ isset($career) ? 'Update' : 'Create' }}
                        </button>
                        <a href="{{ route('admin.careers.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#description-editor')).catch(error => console.error(error));
    ClassicEditor.create(document.querySelector('#requirements-editor')).catch(error => console.error(error));
    ClassicEditor.create(document.querySelector('#benefits-editor')).catch(error => console.error(error));
</script>
@endpush
