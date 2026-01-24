@extends('layouts.frontend')

@section('title', $product->meta_title ?? $product->name)
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('content')
    <!-- Professional Product Detail Section -->
    <section class="bg-gray-50/30 py-16 lg:py-24" id="printable-product">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-16 items-start">
                
                <!-- 5. LEFT COLUMN – PRODUCT IMAGE -->
                <div class="w-full lg:w-5/12 sticky top-32">
                    <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-gray-200/50 border border-gray-100 mb-8 overflow-hidden group">
                        <div class="flex justify-center transition-transform duration-700 group-hover:scale-105">
                            @if($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="max-w-full h-auto rounded-xl object-contain">
                            @else
                                <div class="w-full aspect-square bg-gray-100 flex items-center justify-center rounded-xl">
                                    <i class="fas fa-image text-4xl text-gray-300"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Short Description (Under Image) -->
                    @if($product->short_description)
                        <div class="text-center px-4">
                            <p class="text-gray-500 text-lg leading-relaxed italic line-clamp-3">
                                {{ strip_tags($product->short_description) }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- 6. RIGHT COLUMN – PRODUCT CONTENT -->
                <div class="w-full lg:w-7/12 space-y-12">
                    
                    <!-- 6.1 Product Name -->
                    <div>
                        <h1 class="text-4xl md:text-6xl font-black text-gray-900 leading-tight uppercase tracking-tight">
                            {{ $product->name }}
                        </h1>
                        <div class="w-24 h-2 bg-emerald-600 mt-4 rounded-full"></div>
                    </div>

                    <!-- 6.2 Full Description -->
                    <div class="prose prose-xl max-w-none text-gray-600 leading-loose">
                        <p>{{ strip_tags($product->description) }}</p>
                    </div>

                    <!-- 6.3 Highlights Section -->
                    @if($product->key_benefits)
                        <div class="space-y-6">
                            <h3 class="text-2xl font-black text-emerald-800 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-sm">
                                    <i class="fas fa-star text-emerald-600"></i>
                                </span>
                                Highlights:
                            </h3>
                            <div class="text-gray-700 text-lg space-y-3 pl-2">
                                {!! preg_replace('/<[^>]*>/', "\n", $product->key_benefits) !!}
                            </div>
                        </div>
                    @endif

                    <!-- 6.4 Dosage Section -->
                    @if($product->dosage)
                        <div class="bg-emerald-50 rounded-3xl p-8 border border-emerald-100">
                            <h3 class="text-2xl font-black text-emerald-800 flex items-center gap-3 mb-4">
                                <i class="fas fa-flask text-emerald-600"></i>
                                Doses:
                            </h3>
                            <div class="text-emerald-900 text-lg font-medium">
                                {{ strip_tags($product->dosage) }}
                            </div>
                        </div>
                    @endif

                    <!-- 7. ACTION BUTTONS -->
                    <div class="flex flex-wrap gap-4 pt-8 no-print">
                        <x-frontend.button href="{{ route('products.index') }}" variant="primary" class="bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-4 rounded-2xl font-bold shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-1">
                            View More Products
                        </x-frontend.button>
                        
                        <button onclick="window.print()" class="flex items-center gap-3 bg-white text-gray-700 px-10 py-4 rounded-2xl font-bold border-2 border-emerald-600 hover:bg-emerald-600 hover:text-white transition-all transform hover:-translate-y-1">
                            <i class="fas fa-print"></i>
                            Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Print CSS -->
    <style>
        @media print {
            header, footer, .no-print { display: none !important; }
            body { background: white !important; }
            section { py: 0 !important; }
            .container { max-width: 100% !important; margin: 0 !important; padding: 2rem !important; }
            .flex { display: block !important; }
            .lg\:w-5\/12, .lg\:w-7\/12 { width: 100% !important; margin-bottom: 2rem; position: static !important; }
            h1 { font-size: 32pt !important; }
            .bg-emerald-50 { background: transparent !important; color: black !important; padding: 0 !important; border: none !important; }
            .text-emerald-800 { color: black !important; }
            .prose { font-size: 12pt !important; }
        }
    </style>
@endsection


