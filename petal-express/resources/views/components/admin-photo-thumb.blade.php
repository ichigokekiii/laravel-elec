@props([
    'src',
    'title' => '',
    'size' => 'sm',
])

@php
    $sizeClass = $size === 'lg' ? 'h-24 w-24' : 'h-12 w-12';
@endphp

<button
    type="button"
    aria-label="Preview photo of {{ $title }}"
    {{ $attributes->merge(['class' => 'shrink-0 rounded-xl focus:outline-none focus:ring-2 focus:ring-accent/30 cursor-zoom-in']) }}
    @click="$dispatch('open-photo-preview', { src: @js($src), title: @js($title) })"
>
    <img src="{{ $src }}" alt="" class="{{ $sizeClass }} rounded-xl object-cover border border-gray-100">
</button>
