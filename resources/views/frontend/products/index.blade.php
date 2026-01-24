@extends('layouts.frontend')

@section('title', 'Our Products')

@section('content')
    <!-- Page Header -->
    <section class="gradient-primary py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10 text-center text-white" data-animate="animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 font-heading">Our Products</h1>
            <p class="text-xl text-white/80 max-w-2xl mx-auto">Explore our premium range of products designed for your wellness.</p>
        </div>
    </section>

    <!-- Main Content -->
    <!-- Main Content -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            
            <!-- View Toggle Controls -->
            <div class="view-toggle mb-8 flex justify-end gap-2">
                <button id="gridViewBtn" class="px-4 py-2 rounded-lg border-2 border-primary text-primary font-bold hover:bg-primary hover:text-white transition-all active-view">
                    <i class="fas fa-th-large mr-2"></i> Grid
                </button>
                <button id="listViewBtn" class="px-4 py-2 rounded-lg border-2 border-primary text-primary font-bold hover:bg-primary hover:text-white transition-all">
                    <i class="fas fa-list mr-2"></i> List
                </button>
            </div>

            <!-- Products Container -->
            <div id="productsContainer" class="product-grid">
                @foreach($products as $product)
                    <div class="product-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300">
                        <div class="product-img-container aspect-square bg-gray-50 relative overflow-hidden">
                            @if($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="fas fa-image text-4xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $product->name }}</h4>
                            <p class="text-gray-600 mb-6 line-clamp-2">{{ Str::limit($product->short_description, 100) }}</p>
                            <div class="flex items-center justify-between">
                                <a href="{{ route('products.show', $product->slug) }}" class="bg-primary text-white px-6 py-3 rounded-full font-bold hover:bg-primary/90 transition-all text-center">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>

    <style>
        /* GRID VIEW */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }

        .product-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        /* LIST VIEW */
        .product-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .product-list .product-card {
            display: flex;
            flex-direction: row;
            align-items: center;
            height: auto;
        }

        .product-list .product-card .product-img-container {
            width: 250px;
            height: 250px;
            flex-shrink: 0;
        }

        .product-list .product-card .p-6 {
            flex-grow: 1;
        }

        @media (max-width: 768px) {
            .product-list .product-card {
                flex-direction: column;
            }
            .product-list .product-card .product-img-container {
                width: 100%;
                aspect-ratio: 16/9;
            }
        }

        /* Toggles styles */
        .active-view {
            background-color: var(--primary-color, #10b981);
            color: white !important;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const gridBtn = document.getElementById('gridViewBtn');
        const listBtn = document.getElementById('listViewBtn');
        const container = document.getElementById('productsContainer');

        // Check for saved preference
        const savedView = localStorage.getItem('productView');
        if (savedView === 'list') {
            enableListView();
        }

        gridBtn.addEventListener('click', function () {
            enableGridView();
        });

        listBtn.addEventListener('click', function () {
            enableListView();
        });

        function enableGridView() {
            container.classList.remove('product-list');
            container.classList.add('product-grid');
            gridBtn.classList.add('active-view');
            listBtn.classList.remove('active-view');
            localStorage.setItem('productView', 'grid');
        }

        function enableListView() {
            container.classList.remove('product-grid');
            container.classList.add('product-list');
            listBtn.classList.add('active-view');
            gridBtn.classList.remove('active-view');
            localStorage.setItem('productView', 'list');
        }
    });
    </script>
@endsection
