@extends('layouts.frontend')

@section('title', 'Contact Us - ' . ($siteSettings['site_title'] ?? 'IT Business'))

@section('content')
    <!-- Page Header -->
    <section class="gradient-primary py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"1\" fill=\"white\"/></svg>'); background-size: 50px 50px;"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4" data-animate="animate-fade-in-up">Contact Us</h1>
                <p class="text-xl text-white/80 max-w-2xl mx-auto" data-animate="animate-fade-in-up" data-delay="200">
                    Have a question or want to work together? We'd love to hear from you.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Contact Info -->
                <div class="space-y-8" data-animate="animate-fade-in-left">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Get In Touch</h2>
                        <p class="text-gray-600 mb-8">We're here to help and answer any question you might have. We look forward to hearing from you.</p>
                    </div>

                    @if($contactSettings['address'] ?? null)
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 gradient-primary rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Address</h3>
                                <p class="text-gray-600">{{ $contactSettings['address'] }}</p>
                            </div>
                        </div>
                    @endif

                    @if($contactSettings['phone'] ?? null)
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 gradient-primary rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Phone</h3>
                                <a href="tel:{{ $contactSettings['phone'] }}" class="text-gray-600 hover:text-primary">{{ $contactSettings['phone'] }}</a>
                            </div>
                        </div>
                    @endif

                    @if($contactSettings['email'] ?? null)
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 gradient-primary rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Email</h3>
                                <a href="mailto:{{ $contactSettings['email'] }}" class="text-gray-600 hover:text-primary">{{ $contactSettings['email'] }}</a>
                            </div>
                        </div>
                    @endif

                    @if($contactSettings['working_hours'] ?? null)
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 gradient-primary rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Working Hours</h3>
                                <p class="text-gray-600">{{ $contactSettings['working_hours'] }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2" data-animate="animate-fade-in-right">
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Send Us a Message</h2>

                        @if(session('success'))
                            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-6">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST" id="contact-form">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Name *</label>
                                    <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror" placeholder="John Doe" value="{{ old('name') }}">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror" placeholder="john@example.com" value="{{ old('email') }}">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary" placeholder="+1 234 567 890" value="{{ old('phone') }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                    <input type="text" name="subject" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary" placeholder="How can we help?" value="{{ old('subject') }}">
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Your Message *</label>
                                <textarea name="message" rows="5" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary @error('message') border-red-500 @enderror" placeholder="Tell us about your project...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="gradient-primary text-white px-8 py-4 rounded-xl font-semibold hover:shadow-lg transition-all">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    @if($contactSettings['map_embed'] ?? null)
        <section class="pb-20">
            <div class="container mx-auto px-4">
                <div class="rounded-2xl overflow-hidden shadow-lg">
                    {!! $contactSettings['map_embed'] !!}
                </div>
            </div>
        </section>
    @endif
@endsection
