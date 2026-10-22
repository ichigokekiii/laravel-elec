@props([
    'heading',
    'intro' => null,
])

<x-app-layout>
    <x-slot name="header">
        <div class="max-w-3xl text-left">
            <h2 class="font-playfair text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight leading-tight">{{ $heading }}</h2>
            @if ($intro)
                <p class="mt-2 text-sm sm:text-base text-gray-600 leading-relaxed max-w-[65ch]">{{ $intro }}</p>
            @endif
        </div>
    </x-slot>

    <div class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            {{ $slot }}
        </div>
    </div>
</x-app-layout>
