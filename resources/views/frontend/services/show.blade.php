@extends('layouts.frontend')

@section('title', $service->meta_title ?? $service->name)
@section('meta_description', $service->meta_description ?? $service->short_description)

@section('content')
    <!-- Service Banner -->
    <section class="relative py-32 overflow-hidden">
        @if($service->banner_image)
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $service->banner_image) }}')"></div>
        @else
            <div class="absolute inset-0 gradient-primary"></div>
        @endif
        <div class="absolute inset-0 bg-black/50"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center text-white max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-5xl font-bold mb-4" data-animate="animate-fade-in-up">{{ $service->name }}</h1>
                @if($service->short_description)
                    <p class="text-xl text-white/80" data-animate="animate-fade-in-up" data-delay="200">{{ $service->short_description }}</p>
                @endif
            </div>
        </div>
    </section>

    <!-- Service Content -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="w-full rounded-2xl shadow-lg mb-8">
                    @endif

                    <div class="prose prose-lg max-w-none">
                        {!! $service->description !!}
                    </div>
                </div>

                <!-- Sidebar -->
                <div>
                    <!-- Enquiry Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Request This Service</h3>
                        <p class="text-gray-600 mb-6">Interested in this service? Fill out the form and we'll get back to you.</p>

                        <button onclick="openEnquiryModal()" class="w-full gradient-primary text-white py-4 rounded-xl font-semibold hover:shadow-lg transition-all">
                            Request Service
                        </button>

                        @if($contactSettings['phone'] ?? null)
                            <div class="mt-6 pt-6 border-t">
                                <p class="text-sm text-gray-500 mb-2">Or call us directly:</p>
                                <a href="tel:{{ $contactSettings['phone'] }}" class="text-lg font-bold text-primary">{{ $contactSettings['phone'] }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Services -->
    @if($relatedServices->count() > 0)
        <section class="py-20">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Related Services</h2>
                    <div class="w-20 h-1 gradient-primary mx-auto rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedServices as $related)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift">
                            @if($related->image)
                                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $related->name }}</h3>
                                <a href="{{ route('services.show', $related->slug) }}" class="text-primary font-semibold">Learn More</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Enquiry Modal -->
    <div id="enquiry-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeEnquiryModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative">
                <button onclick="closeEnquiryModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <h3 class="text-2xl font-bold text-gray-900 mb-6">Request Service</h3>

                <form id="enquiry-form" action="{{ route('services.enquiry') }}" method="POST">
                    @csrf
                    <input type="hidden" name="service_id" value="{{ $service->id }}">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number *</label>
                            <input type="tel" name="mobile" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Your mobile number">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email (Optional)</label>
                            <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Your email address">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Any additional requirements..."></textarea>
                        </div>

                        <button type="submit" class="w-full gradient-primary text-white py-4 rounded-xl font-semibold hover:shadow-lg transition-all">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEnquiryModal() {
            document.getElementById('enquiry-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEnquiryModal() {
            document.getElementById('enquiry-modal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.getElementById('enquiry-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeEnquiryModal();
                    alert(data.message);
                    form.reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
@endsection
