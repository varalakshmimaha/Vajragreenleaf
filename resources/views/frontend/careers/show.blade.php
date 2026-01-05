@extends('layouts.frontend')

@section('title', $career->meta_title ?? $career->title)
@section('meta_description', $career->meta_description ?? $career->short_description)

@section('content')
    <!-- Hero Section -->
    <section class="gradient-primary py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-white animate-fade-in">
                <a href="{{ route('careers.index') }}" class="inline-flex items-center text-white/80 hover:text-white mb-6 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to All Jobs
                </a>

                @if($career->is_featured)
                    <span class="inline-block px-3 py-1 bg-yellow-400 text-yellow-900 text-xs font-semibold rounded-full mb-4">
                        <i class="fas fa-star mr-1"></i>Featured Position
                    </span>
                @endif

                <h1 class="text-3xl md:text-5xl font-bold mb-4 font-heading">{{ $career->title }}</h1>

                @if($career->department)
                    <p class="text-xl text-white/80 mb-6">{{ $career->department }}</p>
                @endif

                <div class="flex flex-wrap gap-4 mb-8">
                    <span class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full text-sm backdrop-blur-sm">
                        <i class="fas fa-map-marker-alt mr-2"></i>{{ $career->location }}
                    </span>
                    <span class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full text-sm backdrop-blur-sm">
                        <i class="fas fa-clock mr-2"></i>{{ $career->getJobTypeLabel() }}
                    </span>
                    <span class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full text-sm backdrop-blur-sm">
                        <i class="fas fa-layer-group mr-2"></i>{{ $career->getExperienceLevelLabel() }}
                    </span>
                    @if($career->salary_range)
                        <span class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full text-sm backdrop-blur-sm">
                            <i class="fas fa-money-bill-wave mr-2"></i>{{ $career->salary_range }}
                        </span>
                    @endif
                </div>

                @if($career->isOpen())
                    <button onclick="document.getElementById('apply-form').scrollIntoView({behavior: 'smooth'})" class="bg-white text-primary px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                        Apply Now <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                @else
                    <span class="inline-block px-6 py-3 bg-red-500/20 text-white rounded-full">
                        <i class="fas fa-times-circle mr-2"></i>Applications Closed
                    </span>
                @endif
            </div>
        </div>
    </section>

    <!-- Job Details -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-10">
                        @if($career->short_description)
                            <div class="card-modern p-6" data-animate="animate-fade-in-up">
                                <p class="text-lg text-gray-700 leading-relaxed">{{ $career->short_description }}</p>
                            </div>
                        @endif

                        <div data-animate="animate-fade-in-up">
                            <h2 class="text-2xl font-bold mb-4 font-heading">Job Description</h2>
                            <div class="prose prose-lg max-w-none">
                                {!! $career->description !!}
                            </div>
                        </div>

                        @if($career->requirements)
                            <div data-animate="animate-fade-in-up">
                                <h2 class="text-2xl font-bold mb-4 font-heading">Requirements</h2>
                                <div class="prose prose-lg max-w-none">
                                    {!! $career->requirements !!}
                                </div>
                            </div>
                        @endif

                        @if($career->benefits)
                            <div data-animate="animate-fade-in-up">
                                <h2 class="text-2xl font-bold mb-4 font-heading">Benefits</h2>
                                <div class="prose prose-lg max-w-none">
                                    {!! $career->benefits !!}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="card-modern p-6 sticky top-24" data-animate="animate-fade-in-right">
                            <h3 class="font-bold text-lg mb-4">Job Overview</h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <i class="fas fa-calendar-alt w-6 text-primary mt-1"></i>
                                    <div>
                                        <span class="text-sm text-gray-500">Posted</span>
                                        <p class="font-medium">{{ $career->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                @if($career->application_deadline)
                                    <div class="flex items-start">
                                        <i class="fas fa-hourglass-end w-6 text-primary mt-1"></i>
                                        <div>
                                            <span class="text-sm text-gray-500">Deadline</span>
                                            <p class="font-medium">{{ $career->application_deadline->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex items-start">
                                    <i class="fas fa-users w-6 text-primary mt-1"></i>
                                    <div>
                                        <span class="text-sm text-gray-500">Openings</span>
                                        <p class="font-medium">{{ $career->positions }} {{ Str::plural('Position', $career->positions) }}</p>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-6">

                            <h3 class="font-bold text-lg mb-4">Share this Job</h3>
                            <div class="flex gap-3">
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($career->title) }}" target="_blank" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-blue-500 hover:text-white transition-colors">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($career->title) }}" target="_blank" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-blue-700 hover:text-white transition-colors">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-blue-600 hover:text-white transition-colors">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Application Form -->
    @if($career->isOpen())
        <section id="apply-form" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl mx-auto">
                    <div class="text-center mb-10" data-animate="animate-fade-in">
                        <h2 class="text-3xl font-bold mb-4 font-heading">Apply for this Position</h2>
                        <p class="text-gray-600">Fill out the form below to submit your application</p>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl mb-8">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-2xl mr-3"></i>
                                <div>
                                    <h4 class="font-bold">Application Submitted!</h4>
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('careers.apply', $career->slug) }}" method="POST" enctype="multipart/form-data" class="card-modern p-8" data-animate="animate-fade-in-up">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Resume/CV *</label>
                                <input type="file" name="resume" accept=".pdf,.doc,.docx" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('resume') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX (Max 5MB)</p>
                                @error('resume')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn Profile</label>
                                <input type="url" name="linkedin_url" value="{{ old('linkedin_url') }}" placeholder="https://linkedin.com/in/..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Portfolio URL</label>
                                <input type="url" name="portfolio_url" value="{{ old('portfolio_url') }}" placeholder="https://..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Company</label>
                                <input type="text" name="current_company" value="{{ old('current_company') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Position</label>
                                <input type="text" name="current_position" value="{{ old('current_position') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expected Salary</label>
                                <input type="text" name="expected_salary" value="{{ old('expected_salary') }}" placeholder="e.g., $80,000 - $100,000"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Available From</label>
                                <input type="date" name="available_from" value="{{ old('available_from') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cover Letter</label>
                            <textarea name="cover_letter" rows="5" placeholder="Tell us why you're the perfect fit for this role..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">{{ old('cover_letter') }}</textarea>
                        </div>

                        <button type="submit" class="w-full btn-primary py-4 text-lg">
                            <i class="fas fa-paper-plane mr-2"></i>Submit Application
                        </button>
                    </form>
                </div>
            </div>
        </section>
    @endif

    <!-- Related Jobs -->
    @if($relatedJobs->count() > 0)
        <section class="py-16">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12 font-heading" data-animate="animate-fade-in">Related Positions</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    @foreach($relatedJobs as $job)
                        <a href="{{ route('careers.show', $job->slug) }}" class="card-modern p-6 hover-lift" data-animate="animate-fade-in-up">
                            <h3 class="font-bold text-lg mb-2">{{ $job->title }}</h3>
                            <div class="flex flex-wrap gap-2 text-sm text-gray-500">
                                <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $job->location }}</span>
                                <span><i class="fas fa-clock mr-1"></i>{{ $job->getJobTypeLabel() }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
