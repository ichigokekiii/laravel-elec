@props([
    'title' => '',
])

@php
    $shell = 'max-w-screen-2xl';
@endphp

<x-app-layout :shell="$shell">
    <x-slot name="header">
        <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sm:gap-6">
            <h2 class="font-playfair text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $title }}</h2>
            @isset($actions)
                <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    </x-slot>

    <div class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="{{ $shell }} mx-auto w-full">
            {{ $slot }}
        </div>
    </div>
</x-app-layout>
