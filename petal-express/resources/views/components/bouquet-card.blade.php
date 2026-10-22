@props([
    'name',
    'price',
    'href',
    'image' => 'https://images.unsplash.com/photo-1519378058457-4c29a0a2efac?q=80&w=800',
])

<article {{ $attributes->merge(['class' => 'group bg-white p-6 sm:p-8 rounded-[2rem] border border-gray-200 hover:shadow-xl transition']) }}>
    <div class="relative overflow-hidden rounded-[1.5rem] h-[240px] mb-6">
        <img src="{{ $image }}" alt="{{ $name }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-110">
        @isset($badge)
            <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest text-gray-900">
                {{ $badge }}
            </span>
        @endisset
    </div>

    <h3 class="font-playfair text-xl font-bold text-gray-900">{{ $name }}</h3>
    <p class="mt-1 text-lg font-semibold text-accent">{{ $price }}</p>

    {{-- default slot = card body. named $badge is the little label above --}}
    <div class="mt-4 text-sm text-gray-600 leading-relaxed">
        {{ $slot }}
    </div>

    <div class="mt-8">
        <a href="{{ $href }}" class="inline-flex min-h-11 items-center rounded-full bg-gray-900 text-white px-6 text-xs font-semibold uppercase tracking-wide border-2 border-gray-900 hover:bg-transparent hover:text-gray-900 transition">
            View bouquet
        </a>
    </div>
</article>
