<footer class="bg-gray-900 text-gray-300">
    <!-- Main Footer -->
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <!-- Company Info -->
            <div class="lg:col-span-1">
                @if(!empty($siteSettings['logo_light']))
                    <img src="{{ asset('storage/' . $siteSettings['logo_light']) }}" alt="{{ $siteSettings['site_title'] ?? 'Logo' }}" class="h-10 mb-6">
                @else
                    <h3 class="text-2xl font-bold text-white mb-6">{{ $siteSettings['site_title'] ?? 'IT Business' }}</h3>
                @endif

                <p class="text-gray-400 mb-6">
                    {{ $siteSettings['footer_text'] ?? 'Building innovative digital solutions for businesses worldwide.' }}
                </p>

                <!-- Social Links -->
                <div class="flex space-x-4">
                    @if(!empty($socialLinks['facebook']))
                        <a href="{{ $socialLinks['facebook'] }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77,7.46H14.5v-1.9c0-.9.6-1.1,1-1.1h3V.5h-4.33C10.24.5,9.5,3.44,9.5,5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4Z"/></svg>
                        </a>
                    @endif
                    @if(!empty($socialLinks['twitter']))
                        <a href="{{ $socialLinks['twitter'] }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953,4.57a10,10,0,0,1-2.825.775,4.958,4.958,0,0,0,2.163-2.723,9.99,9.99,0,0,1-3.127,1.195,4.92,4.92,0,0,0-8.384,4.482A13.978,13.978,0,0,1,1.671,3.149,4.93,4.93,0,0,0,3.195,9.723,4.9,4.9,0,0,1,.964,9.1v.062a4.923,4.923,0,0,0,3.946,4.827,4.9,4.9,0,0,1-2.212.084,4.935,4.935,0,0,0,4.6,3.417A9.868,9.868,0,0,1,0,19.54a13.94,13.94,0,0,0,7.548,2.212A13.9,13.9,0,0,0,21.543,7.82c0-.21,0-.42-.015-.63A10.025,10.025,0,0,0,24,4.59l-.047-.02Z"/></svg>
                        </a>
                    @endif
                    @if(!empty($socialLinks['linkedin']))
                        <a href="{{ $socialLinks['linkedin'] }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447,20.452H16.893V14.883c0-1.328-.027-3.037-1.852-3.037-1.853,0-2.136,1.445-2.136,2.939v5.667H9.351V9h3.414v1.561h.046a3.745,3.745,0,0,1,3.37-1.85c3.6,0,4.267,2.37,4.267,5.455v6.286ZM5.337,7.433A2.064,2.064,0,1,1,7.4,5.368,2.062,2.062,0,0,1,5.337,7.433ZM7.119,20.452H3.555V9H7.119ZM22.225,0H1.771A1.75,1.75,0,0,0,0,1.729V22.271A1.749,1.749,0,0,0,1.771,24H22.222A1.756,1.756,0,0,0,24,22.271V1.729A1.756,1.756,0,0,0,22.222,0Z"/></svg>
                        </a>
                    @endif
                    @if(!empty($socialLinks['instagram']))
                        <a href="{{ $socialLinks['instagram'] }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2.163c3.204,0,3.584.012,4.85.07,3.252.148,4.771,1.691,4.919,4.919.058,1.265.069,1.645.069,4.849s-.012,3.584-.069,4.849c-.149,3.225-1.664,4.771-4.919,4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.849-.07c-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849s.013-3.583.07-4.849C2.381,3.924,3.896,2.38,7.151,2.232,8.417,2.175,8.8,2.163,12,2.163ZM12,0C8.741,0,8.333.014,7.053.072,2.695.272.273,2.69.073,7.052.014,8.333,0,8.741,0,12s.014,3.668.072,4.948c.2,4.358,2.618,6.78,6.98,6.98C8.333,23.986,8.741,24,12,24s3.668-.014,4.948-.072c4.354-.2,6.782-2.618,6.979-6.98.059-1.28.073-1.689.073-4.948s-.014-3.667-.072-4.947c-.196-4.354-2.617-6.78-6.979-6.98C15.668.014,15.259,0,12,0Zm0,5.838A6.162,6.162,0,1,0,18.162,12,6.162,6.162,0,0,0,12,5.838ZM12,16a4,4,0,1,1,4-4A4,4,0,0,1,12,16ZM18.406,4.155a1.44,1.44,0,1,0,1.44,1.44A1.44,1.44,0,0,0,18.406,4.155Z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Footer Menu 1 -->
            @if($footerCol1)
                <div>
                    <h4 class="text-white font-semibold text-lg mb-6">{{ $footerCol1->name }}</h4>
                    <ul class="space-y-3">
                        @foreach($footerCol1->items as $item)
                            <li>
                                <a href="{{ $item->url }}" target="{{ $item->target }}" class="hover:text-primary transition-colors">{{ $item->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Footer Menu 2 -->
            @if($footerCol2)
                <div>
                    <h4 class="text-white font-semibold text-lg mb-6">{{ $footerCol2->name }}</h4>
                    <ul class="space-y-3">
                        @foreach($footerCol2->items as $item)
                            <li>
                                <a href="{{ $item->url }}" target="{{ $item->target }}" class="hover:text-primary transition-colors">{{ $item->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Contact Info -->
            <div>
                <h4 class="text-white font-semibold text-lg mb-6">Contact Us</h4>
                <ul class="space-y-4">
                    @if(!empty($contactSettings['address']))
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 mt-1 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $contactSettings['address'] }}</span>
                        </li>
                    @endif
                    @if(!empty($contactSettings['phone']))
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <a href="tel:{{ $contactSettings['phone'] }}" class="hover:text-primary transition-colors">{{ $contactSettings['phone'] }}</a>
                        </li>
                    @endif
                    @if(!empty($contactSettings['email']))
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:{{ $contactSettings['email'] }}" class="hover:text-primary transition-colors">{{ $contactSettings['email'] }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-800">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} {{ $siteSettings['site_title'] ?? 'IT Business' }}. All rights reserved.
                </p>
                @if($footerCol3)
                    <div class="flex space-x-6 text-sm">
                        @foreach($footerCol3->items as $item)
                            <a href="{{ $item->url }}" target="{{ $item->target }}" class="text-gray-500 hover:text-primary transition-colors">{{ $item->title }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</footer>
