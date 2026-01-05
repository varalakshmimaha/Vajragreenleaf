@extends('layouts.frontend')

@section('title', $product->meta_title ?? $product->name)
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('content')
    <!-- Product Hero -->
    <section class="gradient-primary py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-white" data-animate="animate-fade-in">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 font-heading">{{ $product->name }}</h1>
                    <p class="text-xl text-white/80 mb-8">{{ $product->short_description }}</p>

                    <div class="flex items-baseline mb-8">
                        <span class="text-5xl font-bold">${{ number_format($product->price, 0) }}</span>
                        @if($product->price_label)
                            <span class="text-xl text-white/70 ml-2">{{ $product->price_label }}</span>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <button onclick="openEnquiryModal()" class="bg-white text-primary px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors">
                            Request Demo
                        </button>
                        @if($product->pdf_file)
                            <a href="{{ asset('storage/' . $product->pdf_file) }}" target="_blank" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-primary transition-colors flex items-center">
                                <i class="fas fa-file-pdf mr-2"></i> Download Brochure
                            </a>
                        @endif
                    </div>
                </div>

                @if($product->main_image)
                    <div data-animate="animate-fade-in-up">
                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="rounded-xl shadow-2xl">
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Features -->
    @if($product->features && count($product->features) > 0)
        <section class="py-20">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12 font-heading" data-animate="animate-fade-in">Key Features</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($product->features as $feature)
                        <div class="card-modern p-6" data-animate="animate-fade-in-up">
                            <div class="w-12 h-12 gradient-primary rounded-lg flex items-center justify-center mb-4">
                                <i class="fas fa-check-circle text-xl text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 font-heading">{{ $feature['title'] }}</h3>
                            <p class="text-gray-600">{{ $feature['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Video Section -->
    @if($product->video_url || $product->video_file)
        <section class="py-20">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12 animate-fade-in">Product Video</h2>
                <div class="max-w-4xl mx-auto animate-fade-in-up">
                    @if($product->video_url)
                        @php
                            $videoId = null;
                            if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $product->video_url, $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp
                        @if($videoId)
                            <div class="aspect-video rounded-xl overflow-hidden shadow-lg">
                                <iframe src="https://www.youtube.com/embed/{{ $videoId }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                            </div>
                        @else
                            <div class="aspect-video rounded-xl overflow-hidden shadow-lg">
                                <iframe src="{{ $product->video_url }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                            </div>
                        @endif
                    @elseif($product->video_file)
                        <div class="aspect-video rounded-xl overflow-hidden shadow-lg bg-black">
                            <video controls class="w-full h-full">
                                <source src="{{ asset('storage/' . $product->video_file) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- Gallery Section -->
    @if($product->gallery && count($product->gallery) > 0)
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12 animate-fade-in">Gallery</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-w-6xl mx-auto">
                    @foreach($product->gallery as $image)
                        <a href="{{ asset('storage/' . $image) }}" target="_blank" class="block aspect-square overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow animate-fade-in-up">
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Description -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold mb-8 animate-fade-in">About {{ $product->name }}</h2>
                <div class="prose prose-lg max-w-none animate-fade-in-up">
                    {!! $product->description !!}
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-primary text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 font-heading" data-animate="animate-fade-in">Ready to Get Started?</h2>
            <p class="text-xl text-white/80 mb-8 max-w-2xl mx-auto" data-animate="animate-fade-in">Request a demo or contact our sales team for a personalized consultation.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-animate="animate-fade-in-up">
                <button onclick="openEnquiryModal()" class="bg-white text-primary px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors">
                    Request Demo
                </button>
                <a href="{{ route('contact') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                    Contact Sales
                </a>
            </div>
        </div>
    </section>

    <!-- Enquiry Modal -->
    <div id="enquiry-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 animate-scale-in">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold">Request Demo: {{ $product->name }}</h3>
                    <button onclick="closeEnquiryModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('products.enquiry') }}" method="POST" id="enquiry-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number *</label>
                            <input type="tel" name="mobile" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email (Optional)</label>
                            <input type="email" name="email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Message (Optional)</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary"></textarea>
                        </div>

                        <button type="submit" class="w-full btn-primary py-3 rounded-lg font-semibold">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openEnquiryModal() {
        document.getElementById('enquiry-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEnquiryModal() {
        document.getElementById('enquiry-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.getElementById('enquiry-modal').addEventListener('click', function(e) {
        if (e.target === this) closeEnquiryModal();
    });
</script>
@endpush
