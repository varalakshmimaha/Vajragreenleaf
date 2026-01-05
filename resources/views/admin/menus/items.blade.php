@extends('layouts.admin')

@section('title', 'Menu Items - ' . $menu->name)

@push('styles')
<style>
    .menu-item {
        cursor: grab;
        transition: all 0.2s ease;
    }
    .menu-item:active {
        cursor: grabbing;
    }
    .menu-item.dragging {
        opacity: 0.5;
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .menu-item.drag-over {
        border-top: 3px solid #3b82f6;
        margin-top: -3px;
    }
    .child-item {
        cursor: grab;
    }
    .child-item:active {
        cursor: grabbing;
    }
    .child-item.dragging {
        opacity: 0.5;
    }
    .child-item.drag-over {
        border-top: 3px solid #3b82f6;
    }
    .order-badge {
        min-width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .save-indicator {
        transition: all 0.3s ease;
    }
    .save-indicator.saving {
        opacity: 1;
    }
    .save-indicator.saved {
        opacity: 1;
        color: #10b981;
    }
</style>
@endpush

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Menu Items</h1>
            <p class="text-gray-600">{{ $menu->name }} ({{ $menu->location }})</p>
        </div>
        <div class="flex items-center gap-4">
            <span id="saveIndicator" class="save-indicator text-sm opacity-0">
                <i class="fas fa-spinner fa-spin mr-1"></i> Saving...
            </span>
            <a href="{{ route('admin.menus.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
                <i class="fas fa-arrow-left mr-2"></i> Back to Menus
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add Item Form -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-4">Add Menu Item</h2>

            <form action="{{ route('admin.menus.items.store', $menu) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL *</label>
                    <input type="text" name="url" placeholder="/ or /services or https://..." required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Parent Item</label>
                    <select name="parent_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">None (Top Level)</option>
                        @foreach($menu->items->whereNull('parent_id') as $item)
                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target</label>
                    <select name="target" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="_self">Same Window</option>
                        <option value="_blank">New Window</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Icon (FontAwesome class)</label>
                    <input type="text" name="icon" placeholder="e.g., fas fa-home"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i> Add Item
                </button>
            </form>
        </div>

        <!-- Menu Items List with Drag & Drop -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Menu Items</h2>
                <p class="text-sm text-gray-500">
                    <i class="fas fa-grip-vertical mr-1"></i> Drag to reorder
                </p>
            </div>

            @if($menu->items->count() > 0)
                <div id="menuItemsList" class="space-y-3">
                    @foreach($menu->items->whereNull('parent_id')->sortBy('order') as $index => $item)
                        <div class="menu-item border border-gray-200 rounded-lg bg-white hover:shadow-md transition-shadow"
                             data-id="{{ $item->id }}"
                             data-order="{{ $item->order }}"
                             draggable="true">
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-400 cursor-grab hover:text-gray-600">
                                            <i class="fas fa-grip-vertical text-lg"></i>
                                        </span>
                                        <span class="order-badge bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                            {{ $item->order }}
                                        </span>
                                        @if($item->icon)
                                            <i class="{{ $item->icon }} text-gray-500"></i>
                                        @endif
                                        <div>
                                            <span class="font-medium text-gray-900">{{ $item->title }}</span>
                                            <span class="text-sm text-gray-500 ml-2">{{ $item->url }}</span>
                                        </div>
                                        @if(!$item->is_active)
                                            <span class="px-2 py-1 bg-red-100 text-red-600 text-xs rounded-full">Inactive</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                onclick="openEditModal({{ json_encode($item) }})"
                                                class="text-blue-600 hover:text-blue-800 p-2">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.menus.items.destroy', [$menu, $item]) }}" method="POST"
                                              onsubmit="return confirm('Delete this item and all its children?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-2">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Child Items -->
                                @if($item->children->count() > 0)
                                    <div class="ml-8 mt-3 space-y-2 border-l-2 border-gray-200 pl-4"
                                         id="children-{{ $item->id }}">
                                        @foreach($item->children->sortBy('order') as $child)
                                            <div class="child-item flex items-center justify-between bg-gray-50 p-3 rounded-lg hover:bg-gray-100"
                                                 data-id="{{ $child->id }}"
                                                 data-parent-id="{{ $item->id }}"
                                                 data-order="{{ $child->order }}"
                                                 draggable="true">
                                                <div class="flex items-center gap-3">
                                                    <span class="text-gray-400 cursor-grab hover:text-gray-600">
                                                        <i class="fas fa-grip-vertical"></i>
                                                    </span>
                                                    <span class="order-badge bg-gray-200 text-gray-600 text-xs font-semibold rounded-full">
                                                        {{ $child->order }}
                                                    </span>
                                                    @if($child->icon)
                                                        <i class="{{ $child->icon }} text-gray-400 text-sm"></i>
                                                    @endif
                                                    <div>
                                                        <span class="text-gray-700">{{ $child->title }}</span>
                                                        <span class="text-xs text-gray-500 ml-2">{{ $child->url }}</span>
                                                    </div>
                                                    @if(!$child->is_active)
                                                        <span class="px-2 py-0.5 bg-red-100 text-red-600 text-xs rounded-full">Inactive</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button type="button"
                                                            onclick="openEditModal({{ json_encode($child) }})"
                                                            class="text-blue-600 hover:text-blue-800 p-1">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </button>
                                                    <form action="{{ route('admin.menus.items.destroy', [$menu, $child]) }}" method="POST"
                                                          onsubmit="return confirm('Delete this item?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 p-1">
                                                            <i class="fas fa-trash text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Quick Order Buttons -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-3">Quick Actions:</p>
                    <div class="flex gap-2">
                        <button onclick="sortAlphabetically()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                            <i class="fas fa-sort-alpha-down mr-1"></i> Sort A-Z
                        </button>
                        <button onclick="reverseOrder()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                            <i class="fas fa-sort-amount-down-alt mr-1"></i> Reverse Order
                        </button>
                    </div>
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-list text-5xl mb-4"></i>
                    <p class="text-lg">No menu items yet.</p>
                    <p class="text-sm mt-2">Add your first menu item using the form on the left.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Edit Menu Item</h3>
                    <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" name="title" id="editTitle" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">URL *</label>
                        <input type="text" name="url" id="editUrl" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parent Item</label>
                        <select name="parent_id" id="editParentId" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">None (Top Level)</option>
                            @foreach($menu->items->whereNull('parent_id') as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target</label>
                        <select name="target" id="editTarget" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="_self">Same Window</option>
                            <option value="_blank">New Window</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon (FontAwesome class)</label>
                        <input type="text" name="icon" id="editIcon" placeholder="e.g., fas fa-home"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" id="editIsActive" value="1"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeEditModal()"
                                class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700">
                            Update Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const menuId = {{ $menu->id }};
    const reorderUrl = '{{ route("admin.menus.reorder", $menu) }}';
    const csrfToken = '{{ csrf_token() }}';

    // Drag and Drop for parent items
    const menuItemsList = document.getElementById('menuItemsList');
    let draggedItem = null;

    if (menuItemsList) {
        const menuItems = menuItemsList.querySelectorAll('.menu-item');

        menuItems.forEach(item => {
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragend', handleDragEnd);
            item.addEventListener('dragover', handleDragOver);
            item.addEventListener('dragleave', handleDragLeave);
            item.addEventListener('drop', handleDrop);
        });

        // Child items drag and drop
        const childItems = menuItemsList.querySelectorAll('.child-item');
        childItems.forEach(item => {
            item.addEventListener('dragstart', handleChildDragStart);
            item.addEventListener('dragend', handleDragEnd);
            item.addEventListener('dragover', handleChildDragOver);
            item.addEventListener('dragleave', handleDragLeave);
            item.addEventListener('drop', handleChildDrop);
        });
    }

    function handleDragStart(e) {
        draggedItem = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', this.dataset.id);
    }

    function handleChildDragStart(e) {
        e.stopPropagation();
        draggedItem = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', this.dataset.id);
    }

    function handleDragEnd(e) {
        this.classList.remove('dragging');
        document.querySelectorAll('.drag-over').forEach(el => el.classList.remove('drag-over'));
        draggedItem = null;
    }

    function handleDragOver(e) {
        e.preventDefault();
        if (draggedItem && draggedItem !== this && draggedItem.classList.contains('menu-item')) {
            this.classList.add('drag-over');
        }
    }

    function handleChildDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        if (draggedItem && draggedItem !== this && draggedItem.classList.contains('child-item')) {
            const draggedParentId = draggedItem.dataset.parentId;
            const thisParentId = this.dataset.parentId;
            if (draggedParentId === thisParentId) {
                this.classList.add('drag-over');
            }
        }
    }

    function handleDragLeave(e) {
        this.classList.remove('drag-over');
    }

    function handleDrop(e) {
        e.preventDefault();
        this.classList.remove('drag-over');

        if (draggedItem && draggedItem !== this && draggedItem.classList.contains('menu-item')) {
            const allItems = [...menuItemsList.querySelectorAll('.menu-item')];
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

    function handleChildDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('drag-over');

        if (draggedItem && draggedItem !== this && draggedItem.classList.contains('child-item')) {
            const draggedParentId = draggedItem.dataset.parentId;
            const thisParentId = this.dataset.parentId;

            if (draggedParentId === thisParentId) {
                const container = this.parentNode;
                const allChildren = [...container.querySelectorAll('.child-item')];
                const draggedIndex = allChildren.indexOf(draggedItem);
                const droppedIndex = allChildren.indexOf(this);

                if (draggedIndex < droppedIndex) {
                    container.insertBefore(draggedItem, this.nextSibling);
                } else {
                    container.insertBefore(draggedItem, this);
                }

                updateOrder();
            }
        }
    }

    function updateOrder() {
        showSaving();

        const items = [];

        // Get parent items order
        const parentItems = menuItemsList.querySelectorAll('.menu-item');
        parentItems.forEach((item, index) => {
            items.push({
                id: parseInt(item.dataset.id),
                order: index,
                parent_id: null
            });

            // Update visual order badge
            const badge = item.querySelector('.order-badge');
            if (badge) badge.textContent = index;

            // Get child items order for this parent
            const childContainer = item.querySelector('[id^="children-"]');
            if (childContainer) {
                const childItems = childContainer.querySelectorAll('.child-item');
                childItems.forEach((child, childIndex) => {
                    items.push({
                        id: parseInt(child.dataset.id),
                        order: childIndex,
                        parent_id: parseInt(item.dataset.id)
                    });

                    // Update visual order badge
                    const childBadge = child.querySelector('.order-badge');
                    if (childBadge) childBadge.textContent = childIndex;
                });
            }
        });

        // Send to server
        fetch(reorderUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ items: items })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSaved();
            } else {
                alert('Failed to save order. Please refresh and try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to save order. Please refresh and try again.');
        });
    }

    function showSaving() {
        const indicator = document.getElementById('saveIndicator');
        indicator.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
        indicator.classList.remove('saved');
        indicator.classList.add('saving');
        indicator.style.opacity = '1';
    }

    function showSaved() {
        const indicator = document.getElementById('saveIndicator');
        indicator.innerHTML = '<i class="fas fa-check mr-1"></i> Saved!';
        indicator.classList.remove('saving');
        indicator.classList.add('saved');

        setTimeout(() => {
            indicator.style.opacity = '0';
        }, 2000);
    }

    // Quick sort functions
    function sortAlphabetically() {
        const items = [...menuItemsList.querySelectorAll('.menu-item')];
        items.sort((a, b) => {
            const titleA = a.querySelector('.font-medium').textContent.toLowerCase();
            const titleB = b.querySelector('.font-medium').textContent.toLowerCase();
            return titleA.localeCompare(titleB);
        });

        items.forEach(item => menuItemsList.appendChild(item));
        updateOrder();
    }

    function reverseOrder() {
        const items = [...menuItemsList.querySelectorAll('.menu-item')];
        items.reverse();
        items.forEach(item => menuItemsList.appendChild(item));
        updateOrder();
    }

    // Edit Modal functions
    function openEditModal(item) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');

        form.action = '{{ url("admin/menus/items") }}/' + item.id;
        document.getElementById('editTitle').value = item.title;
        document.getElementById('editUrl').value = item.url || '';
        document.getElementById('editParentId').value = item.parent_id || '';
        document.getElementById('editTarget').value = item.target || '_self';
        document.getElementById('editIcon').value = item.icon || '';
        document.getElementById('editIsActive').checked = item.is_active;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
        }
    });

    // Close modal on outside click
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endpush
