@props([
    'heading',
    'intro' => null,
])

@php
    $shell = 'max-w-4xl';
@endphp

<x-app-layout :shell="$shell">
    <x-slot name="header">
        <div class="{{ $shell }} w-full">
            <h2 class="font-playfair text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $heading }}</h2>
            @if ($intro)
                <p class="mt-3 text-center text-sm text-gray-600 leading-relaxed sm:text-left sm:text-base max-w-[65ch]">
                    {{ $intro }}
                </p>
            @endif
        </div>
    </x-slot>

    <div class="px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
        <div class="{{ $shell }} mx-auto w-full space-y-8">
            {{ $slot }}
        </div>
    </div>
</x-app-layout>
