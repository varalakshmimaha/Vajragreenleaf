@extends('layouts.frontend')

@section('title', 'Careers - Join Our Team')

@section('content')
    <!-- Hero Section -->
    <section class="relative gradient-primary py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center text-white animate-fade-in">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Join Our Team</h1>
                <p class="text-xl text-white/80 max-w-2xl mx-auto mb-8">Build your career with us. We're looking for talented individuals who are passionate about technology and innovation.</p>
                <div class="flex justify-center gap-4">
                    <span class="px-4 py-2 bg-white/20 rounded-full text-sm backdrop-blur-sm">
                        <i class="fas fa-briefcase mr-2"></i>{{ $careers->total() }} Open Positions
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Listings -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            @if($careers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($careers as $career)
                        <div class="card-modern p-6 hover-lift" data-animate="animate-fade-in-up">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    @if($career->is_featured)
                                        <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full mb-2">
                                            <i class="fas fa-star mr-1"></i>Featured
                                        </span>
                                    @endif
                                    <h3 class="text-xl font-bold text-gray-900 font-heading">{{ $career->title }}</h3>
                                    @if($career->department)
                                        <p class="text-sm text-gray-500">{{ $career->department }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex items-center text-gray-600 text-sm">
                                    <i class="fas fa-map-marker-alt w-5 text-primary"></i>
                                    <span>{{ $career->location }}</span>
                                </div>
                                <div class="flex items-center text-gray-600 text-sm">
                                    <i class="fas fa-clock w-5 text-primary"></i>
                                    <span>{{ $career->getJobTypeLabel() }}</span>
                                </div>
                                <div class="flex items-center text-gray-600 text-sm">
                                    <i class="fas fa-layer-group w-5 text-primary"></i>
                                    <span>{{ $career->getExperienceLevelLabel() }}</span>
                                </div>
                                @if($career->salary_range)
                                    <div class="flex items-center text-gray-600 text-sm">
                                        <i class="fas fa-money-bill-wave w-5 text-primary"></i>
                                        <span>{{ $career->salary_range }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($career->short_description)
                                <p class="text-gray-600 text-sm mb-6 line-clamp-2">{{ $career->short_description }}</p>
                            @endif

                            <div class="flex items-center justify-between pt-4 border-t">
                                @if($career->application_deadline)
                                    <span class="text-xs text-gray-500">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        Apply by {{ $career->application_deadline->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-xs text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>Open
                                    </span>
                                @endif
                                <a href="{{ route('careers.show', $career->slug) }}" class="text-primary font-semibold hover:underline text-sm">
                                    View Details <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($careers->hasPages())
                    <div class="mt-12">
                        {{ $careers->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-briefcase text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Open Positions</h3>
                    <p class="text-gray-600 max-w-md mx-auto">We don't have any open positions at the moment. Please check back later or send us your resume for future opportunities.</p>
                    <a href="{{ route('contact') }}" class="inline-block mt-6 btn-primary">
                        Contact Us
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Why Join Us -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-animate="animate-fade-in">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 font-heading">Why Join Us?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">We offer a dynamic work environment with great benefits and opportunities for growth.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center" data-animate="animate-fade-in-up" data-delay="100">
                    <div class="w-16 h-16 gradient-primary rounded-2xl flex items-center justify-center mx-auto mb-4 text-white">
                        <i class="fas fa-rocket text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Career Growth</h3>
                    <p class="text-gray-600 text-sm">Continuous learning and development opportunities</p>
                </div>
                <div class="text-center" data-animate="animate-fade-in-up" data-delay="200">
                    <div class="w-16 h-16 gradient-primary rounded-2xl flex items-center justify-center mx-auto mb-4 text-white">
                        <i class="fas fa-heart text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Health Benefits</h3>
                    <p class="text-gray-600 text-sm">Comprehensive health insurance for you and family</p>
                </div>
                <div class="text-center" data-animate="animate-fade-in-up" data-delay="300">
                    <div class="w-16 h-16 gradient-primary rounded-2xl flex items-center justify-center mx-auto mb-4 text-white">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Work-Life Balance</h3>
                    <p class="text-gray-600 text-sm">Flexible hours and remote work options</p>
                </div>
                <div class="text-center" data-animate="animate-fade-in-up" data-delay="400">
                    <div class="w-16 h-16 gradient-primary rounded-2xl flex items-center justify-center mx-auto mb-4 text-white">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Great Team</h3>
                    <p class="text-gray-600 text-sm">Work with talented and passionate professionals</p>
                </div>
            </div>
        </div>
    </section>
@endsection
