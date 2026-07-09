<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-white font-sans antialiased">

    {{-- NAVBAR --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100" x-data="{ mobileOpen: false }">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-200">
                        V
                    </div>
                    <span class="font-bold text-xl text-gray-900">VenResto</span>
                </div>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('landing.home') }}#features" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Features</a>
                    <a href="{{ route('landing.pricing') }}" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Pricing</a>
                    <a href="{{ route('landing.documentation') }}" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Documentation</a>
                    <a href="{{ route('landing.home') }}#testimonials" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Testimonials</a>
                    <a href="{{ route('landing.home') }}#faq" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">FAQ</a>
                </div>

                {{-- CTA --}}
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('central.login') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('central.signup') }}"
                       class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-200 transition-all hover:shadow-xl hover:shadow-blue-200 hover:-translate-y-0.5">
                        Get Started Free
                    </a>
                </div>

                {{-- Mobile Toggle --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="md:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
                    <i class="bi bi-list text-2xl" x-show="!mobileOpen"></i>
                    <i class="bi bi-x-lg text-xl" x-show="mobileOpen"></i>
                </button>

            </div>

            {{-- Mobile Menu --}}
            <div x-show="mobileOpen" x-transition class="md:hidden border-t border-gray-100 py-4 space-y-2">
                <a href="{{ route('landing.home') }}#features" @click="mobileOpen = false" class="block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">Features</a>
                <a href="{{ route('landing.pricing') }}" @click="mobileOpen = false" class="block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">Pricing</a>
                <a href="{{ route('landing.documentation') }}" @click="mobileOpen = false" class="block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">Documentation</a>
                <a href="{{ route('landing.home') }}#testimonials" @click="mobileOpen = false" class="block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">Testimonials</a>
                <a href="{{ route('landing.home') }}#faq" @click="mobileOpen = false" class="block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">FAQ</a>
                <hr class="my-2">
                <a href="{{ route('central.login') }}" class="block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">Sign In</a>
                <a href="{{ route('central.signup') }}" class="block px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl text-center">Get Started Free</a>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="pt-16">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-white">
        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">

                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">V</div>
                        <span class="font-bold text-xl">VenResto</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                        Complete restaurant management system for modern F&B businesses. Manage multiple outlets, inventory, and maximize profit.
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="#" class="w-9 h-9 bg-gray-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors">
                            <i class="bi bi-twitter-x text-sm"></i>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors">
                            <i class="bi bi-instagram text-sm"></i>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors">
                            <i class="bi bi-linkedin text-sm"></i>
                        </a>
                    </div>
                </div>

                {{-- Product --}}
                <div>
                    <h4 class="font-semibold text-white mb-4">Product</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#pricing" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Changelog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Roadmap</a></li>
                    </ul>
                </div>

                {{-- Company --}}
                <div>
                    <h4 class="font-semibold text-white mb-4">Company</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="{{ route('landing.contact') }}" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                {{-- Backlinks --}}
                <div>
                    <h4 class="font-semibold text-white mb-4">Temukan Kami Di</h4>
                    <div class="flex space-x-3">
                        <a href="https://www.google.com/maps?cid=VENRESTO_GMB_CID" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" alt="Google My Business" class="h-6">
                        </a>
                        <a href="https://www.tripadvisor.com/Restaurant_Review-VENRESTO_TRIPADVISOR_ID" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/TripAdvisor_logo.svg/512px-TripAdvisor_logo.svg.png" alt="TripAdvisor" class="h-6">
                        </a>
                        <a href="https://www.zomato.com/VENRESTO_ZOMATO_ID" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f7/Zomato_logo.svg/512px-Zomato_logo.svg.png" alt="Zomato" class="h-6">
                        </a>
                    </div>
                </div>

                {{-- Legal --}}
                <div>
                    <h4 class="font-semibold text-white mb-4">Legal</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Cookie Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">GDPR</a></li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} VenResto. All rights reserved.
                </p>
                <p class="text-sm text-gray-500">
                    Made with <i class="bi bi-heart-fill text-red-500"></i> for F&B businesses
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
