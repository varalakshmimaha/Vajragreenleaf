@extends('layouts.admin')

@section('title', 'Page Builder - ' . $page->title)

@push('styles')
<style>
    .section-item { cursor: grab; transition: all 0.2s; }
    .section-item:active { cursor: grabbing; }
    .section-item.dragging { opacity: 0.5; transform: scale(1.02); }
    .section-item.drag-over { border-top: 3px solid #3b82f6; }
    .section-type-card { transition: all 0.2s; }
    .section-type-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
</style>
@endpush

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Page Builder</h1>
            <p class="text-gray-600">{{ $page->title }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ url($page->slug) }}" target="_blank" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
                <i class="fas fa-external-link-alt mr-2"></i> View Page
            </a>
            <a href="{{ route('admin.pages.edit', $page) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
                <i class="fas fa-cog mr-2"></i> Page Settings
            </a>
            <a href="{{ route('admin.pages.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
                <i class="fas fa-arrow-left mr-2"></i> Back to Pages
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Page Sections -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-4 border-b flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Page Sections</h2>
                    <span id="saveIndicator" class="text-sm text-gray-500 opacity-0">
                        <i class="fas fa-check text-green-500 mr-1"></i> Saved
                    </span>
                </div>

                <div id="pageSections" class="p-4 min-h-[300px]">
                    @php
                        $allPageSections = $page->pageSections->sortBy('order');
                    @endphp
                    @if($allPageSections->count() > 0)
                        @foreach($allPageSections as $pageSection)
                            @php
                                // Check if it's old section type or new section
                                $isOldType = $pageSection->section_type_id && $pageSection->sectionType;
                                $isNewSection = $pageSection->section_id && $pageSection->section;

                                if (!$isOldType && !$isNewSection) continue;

                                $sectionName = $isOldType ? $pageSection->sectionType->name : $pageSection->section->name;
                                $sectionType = $isOldType ? $pageSection->sectionType->slug : $pageSection->section->layout;
                                $sectionTitle = $isOldType ? ($pageSection->title ?? '') : ($pageSection->section->title ?? '');
                            @endphp
                            <div class="section-item border border-gray-200 rounded-lg mb-3 bg-white hover:shadow-md transition-shadow"
                                 data-id="{{ $pageSection->id }}"
                                 data-order="{{ $pageSection->order }}"
                                 draggable="true">
                                <div class="p-4 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <span class="text-gray-400 cursor-grab">
                                            <i class="fas fa-grip-vertical text-lg"></i>
                                        </span>

                                        <div class="w-16 h-12 {{ $isOldType ? 'bg-gradient-to-r from-purple-500 to-indigo-600' : 'bg-gradient-to-r from-blue-500 to-cyan-500' }} rounded flex items-center justify-center">
                                            @if($isOldType)
                                                @switch($pageSection->sectionType->slug)
                                                    @case('banner')
                                                        <i class="fas fa-images text-white text-lg"></i>
                                                        @break
                                                    @case('about')
                                                        <i class="fas fa-info-circle text-white text-lg"></i>
                                                        @break
                                                    @case('services')
                                                        <i class="fas fa-cogs text-white text-lg"></i>
                                                        @break
                                                    @case('how-we-work')
                                                        <i class="fas fa-project-diagram text-white text-lg"></i>
                                                        @break
                                                    @case('counters')
                                                        <i class="fas fa-chart-bar text-white text-lg"></i>
                                                        @break
                                                    @case('portfolio')
                                                        <i class="fas fa-briefcase text-white text-lg"></i>
                                                        @break
                                                    @case('blog')
                                                        <i class="fas fa-newspaper text-white text-lg"></i>
                                                        @break
                                                    @case('team')
                                                        <i class="fas fa-users text-white text-lg"></i>
                                                        @break
                                                    @case('testimonials')
                                                        <i class="fas fa-quote-right text-white text-lg"></i>
                                                        @break
                                                    @case('clients')
                                                        <i class="fas fa-handshake text-white text-lg"></i>
                                                        @break
                                                    @case('cta')
                                                        <i class="fas fa-bullhorn text-white text-lg"></i>
                                                        @break
                                                    @default
                                                        <i class="fas fa-layer-group text-white text-lg"></i>
                                                @endswitch
                                            @elseif($isNewSection && $pageSection->section->image)
                                                <img src="{{ asset('storage/' . $pageSection->section->image) }}" class="w-full h-full object-cover rounded">
                                            @else
                                                <i class="fas fa-layer-group text-white text-lg"></i>
                                            @endif
                                        </div>

                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $sectionName }}</h3>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="px-2 py-0.5 {{ $isOldType ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} text-xs rounded">
                                                    {{ $isOldType ? 'Dynamic' : 'Static' }}
                                                </span>
                                                <span class="px-2 py-0.5 bg-gray-100 text-gray-800 text-xs rounded">
                                                    {{ ucfirst(str_replace('-', ' ', $sectionType)) }}
                                                </span>
                                                @if($sectionTitle)
                                                    <span class="text-sm text-gray-500">{{ Str::limit($sectionTitle, 30) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <!-- Toggle Active -->
                                        <button type="button"
                                                onclick="toggleSection({{ $pageSection->id }})"
                                                class="p-2 rounded {{ $pageSection->is_active ? 'text-green-600 hover:text-green-800' : 'text-gray-400 hover:text-gray-600' }}"
                                                title="{{ $pageSection->is_active ? 'Active - Click to hide' : 'Hidden - Click to show' }}">
                                            <i class="fas {{ $pageSection->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                        </button>

                                        <!-- Edit Section -->
                                        @if($isOldType)
                                            <button type="button"
                                                    onclick="editOldSection({{ $pageSection->id }})"
                                                    class="p-2 text-blue-600 hover:text-blue-800" title="Edit Settings">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('admin.sections.edit', $pageSection->section) }}"
                                               class="p-2 text-blue-600 hover:text-blue-800" title="Edit Section">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        <!-- Remove from Page -->
                                        <form action="{{ route('admin.sections.remove-from-page', [$page, $pageSection]) }}" method="POST"
                                              onsubmit="return confirm('Remove this section from the page?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:text-red-800" title="Remove from page">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-12 text-gray-500" id="emptyState">
                            <i class="fas fa-layer-group text-5xl mb-4"></i>
                            <p class="text-lg">No sections added yet</p>
                            <p class="text-sm mt-2">Add sections from the panel on the right</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Available Sections -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm sticky top-6">
                <div class="p-4 border-b">
                    <h2 class="text-lg font-semibold">Add Sections</h2>
                </div>

                <div class="p-4 max-h-[700px] overflow-y-auto">
                    <!-- Dynamic Section Types (Old System) -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-purple-700 uppercase tracking-wide mb-3">
                            <i class="fas fa-database mr-2"></i> Dynamic Sections
                        </h3>
                        <p class="text-xs text-gray-500 mb-3">These sections pull data automatically from database</p>

                        <div class="grid grid-cols-2 gap-2">
                            @foreach($sectionTypes as $type)
                                <div class="section-type-card border border-purple-200 rounded-lg p-3 bg-purple-50 hover:border-purple-400 cursor-pointer"
                                     onclick="addOldSection('{{ $type->id }}', '{{ $type->name }}')">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center mb-2">
                                            @switch($type->slug)
                                                @case('banner')
                                                    <i class="fas fa-images text-white"></i>
                                                    @break
                                                @case('about')
                                                    <i class="fas fa-info-circle text-white"></i>
                                                    @break
                                                @case('services')
                                                    <i class="fas fa-cogs text-white"></i>
                                                    @break
                                                @case('how-we-work')
                                                    <i class="fas fa-project-diagram text-white"></i>
                                                    @break
                                                @case('counters')
                                                    <i class="fas fa-chart-bar text-white"></i>
                                                    @break
                                                @case('portfolio')
                                                    <i class="fas fa-briefcase text-white"></i>
                                                    @break
                                                @case('blog')
                                                    <i class="fas fa-newspaper text-white"></i>
                                                    @break
                                                @case('team')
                                                    <i class="fas fa-users text-white"></i>
                                                    @break
                                                @case('testimonials')
                                                    <i class="fas fa-quote-right text-white"></i>
                                                    @break
                                                @case('clients')
                                                    <i class="fas fa-handshake text-white"></i>
                                                    @break
                                                @case('cta')
                                                    <i class="fas fa-bullhorn text-white"></i>
                                                    @break
                                                @case('custom-html')
                                                    <i class="fas fa-code text-white"></i>
                                                    @break
                                                @default
                                                    <i class="fas fa-layer-group text-white"></i>
                                            @endswitch
                                        </div>
                                        <span class="text-xs font-medium text-gray-800">{{ $type->name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Static/Reusable Sections (New System) -->
                    <div>
                        <h3 class="text-sm font-semibold text-blue-700 uppercase tracking-wide mb-3">
                            <i class="fas fa-layer-group mr-2"></i> Custom Sections
                        </h3>
                        <p class="text-xs text-gray-500 mb-3">Reusable sections with custom content</p>

                        @if($availableSections->count() > 0)
                            @foreach($availableSections as $section)
                                <div class="border border-gray-200 rounded-lg p-3 mb-3 hover:border-blue-300 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            @if($section->image)
                                                <img src="{{ asset('storage/' . $section->image) }}"
                                                     class="w-12 h-10 object-cover rounded">
                                            @else
                                                <div class="w-12 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded flex items-center justify-center">
                                                    <i class="fas fa-layer-group text-white text-sm"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h4 class="font-medium text-sm text-gray-900">{{ $section->name }}</h4>
                                                <span class="text-xs text-gray-500">{{ \App\Models\Section::getLayoutOptions()[$section->layout] ?? $section->layout }}</span>
                                            </div>
                                        </div>

                                        <form action="{{ route('admin.sections.add-to-page', $page) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="section_id" value="{{ $section->id }}">
                                            <button type="submit" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded" title="Add to page">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4 text-gray-500">
                                <p class="text-sm">No custom sections available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-4 border-t">
                    <a href="{{ route('admin.sections.create') }}"
                       class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i> Create Custom Section
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Old Section Modal -->
    <div id="editOldSectionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-4 border-b flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="text-lg font-semibold">Edit Section Settings</h3>
                <button onclick="closeOldSectionModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editOldSectionForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="section_type_slug" id="sectionTypeSlug">
                <div class="p-4 space-y-4">
                    <!-- Common Fields -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Section Title (optional)</label>
                        <input type="text" name="title" id="oldSectionTitle" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle (optional)</label>
                        <input type="text" name="subtitle" id="oldSectionSubtitle" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <!-- Items Limit - for services, portfolio, blog, team -->
                    <div id="limitField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Items Limit</label>
                        <input type="number" name="limit" id="oldSectionLimit" class="w-full px-3 py-2 border border-gray-300 rounded-lg" min="1" max="50">
                        <p class="text-xs text-gray-500 mt-1">Number of items to display</p>
                    </div>

                    <!-- About Section Fields -->
                    <div id="aboutFields" class="hidden space-y-4">
                        <hr class="my-4">
                        <h4 class="font-semibold text-gray-800">About Section Content</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                            <textarea name="settings[content]" id="aboutContent" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Main content text..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="settings[description]" id="aboutDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Additional description..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                            <div id="aboutImagePreview" class="mb-2 hidden">
                                <img id="aboutImagePreviewImg" src="" alt="About Image" class="w-32 h-32 object-cover rounded-lg">
                                <label class="flex items-center mt-2">
                                    <input type="checkbox" name="settings[remove_image]" value="1" class="rounded border-gray-300 text-red-600">
                                    <span class="ml-2 text-sm text-red-600">Remove image</span>
                                </label>
                            </div>
                            <input type="file" name="settings[image]" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Features (comma separated)</label>
                            <input type="text" name="settings[features]" id="aboutFeatures" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Feature 1, Feature 2, Feature 3">
                            <p class="text-xs text-gray-500 mt-1">Enter features separated by commas</p>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h5 class="text-sm font-semibold text-gray-700 mb-3">View More Button Settings</h5>
                            <div class="mb-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="settings[show_button]" id="aboutShowButton" value="1" class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-2 text-sm text-gray-700">Show View More Button</span>
                                </label>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                                    <input type="text" name="settings[button_text]" id="aboutButtonText" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Learn More About Us">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Button URL</label>
                                    <input type="text" name="settings[button_url]" id="aboutButtonUrl" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="/about-us">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Section Fields -->
                    <div id="ctaFields" class="hidden space-y-4">
                        <hr class="my-4">
                        <h4 class="font-semibold text-gray-800">CTA Button Settings</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CTA Button Text</label>
                                <input type="text" name="settings[cta_text]" id="ctaText" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CTA Button URL</label>
                                <input type="text" name="settings[cta_url]" id="ctaUrl" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 border-t flex justify-end gap-3 sticky bottom-0 bg-white">
                    <button type="button" onclick="closeOldSectionModal()" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const pageId = {{ $page->id }};
    const reorderUrl = '{{ route("admin.sections.reorder-page-sections", $page) }}';
    const csrfToken = '{{ csrf_token() }}';

    // Drag and Drop
    const container = document.getElementById('pageSections');
    let draggedItem = null;

    document.querySelectorAll('.section-item').forEach(item => {
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragend', handleDragEnd);
        item.addEventListener('dragover', handleDragOver);
        item.addEventListener('dragleave', handleDragLeave);
        item.addEventListener('drop', handleDrop);
    });

    function handleDragStart(e) {
        draggedItem = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    }

    function handleDragEnd(e) {
        this.classList.remove('dragging');
        document.querySelectorAll('.drag-over').forEach(el => el.classList.remove('drag-over'));
        draggedItem = null;
    }

    function handleDragOver(e) {
        e.preventDefault();
        if (draggedItem && draggedItem !== this) {
            this.classList.add('drag-over');
        }
    }

    function handleDragLeave(e) {
        this.classList.remove('drag-over');
    }

    function handleDrop(e) {
        e.preventDefault();
        this.classList.remove('drag-over');

        if (draggedItem && draggedItem !== this) {
            const allItems = [...container.querySelectorAll('.section-item')];
            const draggedIndex = allItems.indexOf(draggedItem);
            const droppedIndex = allItems.indexOf(this);

            if (draggedIndex < droppedIndex) {
                this.parentNode.insertBefore(draggedItem, this.nextSibling);
            } else {
                this.parentNode.insertBefore(draggedItem, this);
            }

            updateOrder();
        }
    }

    function updateOrder() {
        const items = container.querySelectorAll('.section-item');
        const sections = [];

        items.forEach((item, index) => {
            sections.push({
                id: parseInt(item.dataset.id),
                order: index
            });
        });

        fetch(reorderUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ sections: sections })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSaved();
            }
        });
    }

    function showSaved() {
        const indicator = document.getElementById('saveIndicator');
        indicator.style.opacity = '1';
        setTimeout(() => {
            indicator.style.opacity = '0';
        }, 2000);
    }

    function toggleSection(pageSectionId) {
        fetch(`/admin/sections/page-section/${pageSectionId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    // Add old section type
    function addOldSection(typeId, typeName) {
        if (confirm('Add "' + typeName + '" section to this page?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.sections.add-section-type", $page) }}';
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="section_type_id" value="${typeId}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Edit old section modal
    function editOldSection(pageSectionId) {
        fetch(`/admin/page-sections/${pageSectionId}`)
            .then(response => response.json())
            .then(data => {
                const slug = data.section_type?.slug || '';

                // Set common fields
                document.getElementById('oldSectionTitle').value = data.title || '';
                document.getElementById('oldSectionSubtitle').value = data.content?.subtitle || data.settings?.subtitle || '';
                document.getElementById('sectionTypeSlug').value = slug;

                // Hide all conditional fields first
                document.getElementById('limitField').classList.add('hidden');
                document.getElementById('aboutFields').classList.add('hidden');
                document.getElementById('ctaFields').classList.add('hidden');

                // Show/populate fields based on section type
                if (['services', 'portfolio', 'blog', 'team'].includes(slug)) {
                    document.getElementById('limitField').classList.remove('hidden');
                    document.getElementById('oldSectionLimit').value = data.content?.limit || data.settings?.limit || 6;
                }

                if (slug === 'about') {
                    document.getElementById('aboutFields').classList.remove('hidden');
                    document.getElementById('aboutContent').value = data.settings?.content || '';
                    document.getElementById('aboutDescription').value = data.settings?.description || '';

                    // Handle features - could be array or string
                    const features = data.settings?.features;
                    if (Array.isArray(features)) {
                        document.getElementById('aboutFeatures').value = features.join(', ');
                    } else {
                        document.getElementById('aboutFeatures').value = features || '';
                    }

                    // Button settings
                    document.getElementById('aboutShowButton').checked = data.settings?.show_button !== false;
                    document.getElementById('aboutButtonText').value = data.settings?.button_text || 'Learn More About Us';
                    document.getElementById('aboutButtonUrl').value = data.settings?.button_url || '';

                    // Image preview
                    if (data.settings?.image) {
                        document.getElementById('aboutImagePreview').classList.remove('hidden');
                        document.getElementById('aboutImagePreviewImg').src = '/storage/' + data.settings.image;
                    } else {
                        document.getElementById('aboutImagePreview').classList.add('hidden');
                    }
                }

                if (slug === 'cta') {
                    document.getElementById('ctaFields').classList.remove('hidden');
                    document.getElementById('ctaText').value = data.settings?.cta_text || '';
                    document.getElementById('ctaUrl').value = data.settings?.cta_url || '';
                }

                document.getElementById('editOldSectionForm').action = `/admin/page-sections/${pageSectionId}`;
                document.getElementById('editOldSectionModal').classList.remove('hidden');
            });
    }

    function closeOldSectionModal() {
        document.getElementById('editOldSectionModal').classList.add('hidden');
    }
</script>
@endpush
