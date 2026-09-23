<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@isset($title){{ $title }} | @endisset Petal Express</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>[x-cloak]{display:none!important}</style>
    </head>
    <body class="font-sans bg-page text-gray-900 min-h-screen flex flex-col" x-data="{ mobileOpen: false }">
        <nav class="sticky top-0 z-40 w-full border-b border-gray-200 bg-[#ffffffee] backdrop-blur-md">
            <div class="max-w-7xl mx-auto grid grid-cols-[auto_1fr_auto] items-center gap-3 px-4 py-3 md:gap-6 md:px-8 md:py-4">
                <a href="{{ route('home') }}" class="text-lg font-semibold tracking-wide text-brand lowercase">
                    petal express
                </a>

                <div class="hidden md:flex justify-center gap-8 text-sm text-gray-700">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-accent' : 'hover:text-accent' }}">Home</a>
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-accent' : 'hover:text-accent' }}">About</a>
                    <a href="{{ route('bouquets.index') }}" class="{{ request()->routeIs('bouquets.*') ? 'text-accent' : 'hover:text-accent' }}">Showcase</a>
                    <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'text-accent' : 'hover:text-accent' }}">Services</a>
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-accent' : 'hover:text-accent' }}">Contact</a>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" class="md:hidden p-2 text-gray-700" @click="mobileOpen = !mobileOpen" aria-label="Menu">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-accent">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-full border border-gray-200 px-4 py-2 md:px-6 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-accent">
                            Login
                        </a>
                    @endauth
                </div>
            </div>

            <div class="md:hidden border-t border-gray-100 divide-y divide-gray-100 bg-white" x-show="mobileOpen" x-cloak>
                <a href="{{ route('home') }}" class="block px-4 py-3 min-h-11 text-sm text-gray-700">Home</a>
                <a href="{{ route('about') }}" class="block px-4 py-3 min-h-11 text-sm text-gray-700">About</a>
                <a href="{{ route('bouquets.index') }}" class="block px-4 py-3 min-h-11 text-sm text-gray-700">Showcase</a>
                <a href="{{ route('services') }}" class="block px-4 py-3 min-h-11 text-sm text-gray-700">Services</a>
                <a href="{{ route('contact') }}" class="block px-4 py-3 min-h-11 text-sm text-gray-700">Contact</a>
            </div>
        </nav>

        <main class="flex-1">
            {{-- page content lands in $slot from each guest view --}}
            {{ $slot }}
        </main>

        <footer class="mt-12 bg-white px-4 py-10 md:px-8">
            <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 md:grid-cols-4">
                <div class="space-y-5">
                    <h2 class="text-2xl font-semibold tracking-wide text-brand lowercase">petal express</h2>
                    <div class="flex gap-5 text-gray-500">
                        <span aria-hidden="true">Fb</span>
                        <span aria-hidden="true">Ig</span>
                        <span aria-hidden="true">X</span>
                    </div>
                    <a href="mailto:petalexpressotp@gmail.com" class="block text-sm text-gray-600 hover:text-accent">
                        petalexpressotp@gmail.com
                    </a>
                </div>

                <div>
                    <h3 class="mb-4 text-sm font-semibold text-gray-800">Company</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="{{ route('about') }}" class="hover:text-accent">About Us</a></li>
                        <li><a href="{{ route('bouquets.index') }}" class="hover:text-accent">Showcase</a></li>
                        <li><a href="{{ route('services') }}" class="hover:text-accent">Services</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="mb-4 text-sm font-semibold text-gray-800">Support</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="{{ route('contact') }}" class="hover:text-accent">Contact Us</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-accent">FAQs</a></li>
                        @auth
                            <li><a href="{{ route('account.edit') }}" class="hover:text-accent">My Account</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-accent">My Account</a></li>
                        @endauth
                    </ul>
                </div>

                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800">Share us your thoughts!</h3>
                    <a href="{{ route('contact') }}" class="inline-flex rounded-full bg-gray-900 px-6 py-2.5 text-xs font-semibold uppercase tracking-wide text-white hover:bg-accent-hover">
                        Give Feedback
                    </a>
                </div>
            </div>

            <p class="mt-10 text-center text-sm text-gray-500">
                &copy; 2026 Petal Express. All rights reserved.
            </p>
        </footer>
    </body>
</html>
