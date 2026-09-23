@props([
    'title',
    'script' => null,
    'eyebrow' => null,
])

<div {{ $attributes->merge(['class' => 'mb-10 max-w-4xl']) }}>
    @if ($eyebrow)
        <p class="text-sm font-semibold uppercase tracking-widest text-accent mb-4">
            {{ $eyebrow }}
        </p>
    @endif

    <h1 class="font-playfair font-bold text-5xl sm:text-6xl md:text-7xl leading-[1.02] tracking-tight text-gray-900">
        {{ $title }}
        @if ($script)
            <span class="font-dancing font-normal text-6xl sm:text-7xl md:text-8xl text-accent leading-[0.92] -rotate-2 inline-block lg:ml-2 align-baseline">
                {{ $script }}
            </span>
        @endif
    </h1>

    @if (isset($slot) && ! $slot->isEmpty())
        <div class="mt-6 max-w-lg text-base md:text-lg font-light leading-relaxed text-gray-600 space-y-3">
            {{ $slot }}
        </div>
    @endif
</div>
