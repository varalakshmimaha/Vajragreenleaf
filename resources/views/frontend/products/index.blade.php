@extends('layouts.frontend')

@section('title', 'Our Products')

@section('content')
    <!-- Page Header -->
    <section class="gradient-primary py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center text-white" data-animate="animate-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 font-heading">Our Products</h1>
                <p class="text-xl text-white/80 max-w-2xl mx-auto">Discover our suite of powerful software solutions designed to transform your business.</p>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                    <div class="card-modern overflow-hidden group" data-animate="animate-fade-in-up">
                        @if($product->image)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                        @else
                            <div class="h-48 gradient-primary flex items-center justify-center">
                                <i class="fas fa-box text-6xl text-white/50"></i>
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 font-heading">{{ $product->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $product->short_description }}</p>

                            <div class="flex items-baseline mb-4">
                                <span class="text-3xl font-bold text-primary">${{ number_format($product->price, 0) }}</span>
                                @if($product->price_label)
                                    <span class="text-gray-500 ml-1">{{ $product->price_label }}</span>
                                @endif
                            </div>

                            @if($product->features && count($product->features) > 0)
                                <ul class="space-y-2 mb-6">
                                    @foreach(array_slice($product->features, 0, 3) as $feature)
                                        <li class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-check text-green-500 mr-2"></i>
                                            {{ $feature['title'] ?? $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <a href="{{ route('products.show', $product->slug) }}"
                               class="block w-full text-center btn-primary">
                                Learn More
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gray-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 font-heading" data-animate="animate-fade-in">Not Sure Which Product is Right for You?</h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto" data-animate="animate-fade-in">Our team can help you choose the perfect solution for your business needs.</p>
            <a href="{{ route('contact') }}" class="inline-block btn-primary text-lg" data-animate="animate-fade-in-up">
                Contact Sales
            </a>
        </div>
    </section>
@endsection
