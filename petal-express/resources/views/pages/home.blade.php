<x-site-layout title="Home">
    <section class="bg-page pt-24 md:pt-28 pb-24 px-4 sm:px-8">
        <div class="max-w-content mx-auto">
            <div class="lg:grid lg:grid-cols-[minmax(0,1fr)_340px] lg:gap-12 lg:items-end">
                <x-page-banner
                    eyebrow="Floral Discovery"
                    title="Discover the perfect"
                    script="Flowers"
                >
                    <p>Just for you! Fresh arrangements inspired by Petal Express</p>
                </x-page-banner>

                <div class="mt-8 lg:mt-0 lg:pb-6">
                    {{-- tagline comes from PageController::home() --}}
                    <p class="text-base md:text-lg font-light leading-relaxed text-gray-800 mb-2">
                        {{ $tagline }}
                    </p>
                    <p class="text-sm text-gray-500 mb-6">
                        {{ $featuredCount }} bouquets in the showcase. Six of the newest are below.
                    </p>
                    <a href="{{ route('bouquets.index') }}" class="inline-flex items-center rounded-full bg-gray-900 text-white h-14 px-8 text-sm font-semibold tracking-wide uppercase hover:bg-transparent hover:text-gray-900 border-2 border-gray-900 transition">
                        Shop Now
                    </a>
                </div>
            </div>

            <div class="mt-16 grid gap-4 md:grid-cols-3 h-auto md:h-[500px]">
                @foreach ([
                    ['title' => 'Carnations', 'img' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?q=80&w=1200'],
                    ['title' => 'Sunflowers', 'img' => 'https://images.unsplash.com/photo-1455659817273-f96807779a8a?q=80&w=1200'],
                    ['title' => 'Roses', 'img' => 'https://images.unsplash.com/photo-1519378058457-4c29a0a2efac?q=80&w=1200'],
                ] as $i => $hero)
                    <div class="relative overflow-hidden rounded-[2rem] {{ $i === 0 ? 'md:col-span-1' : '' }} min-h-[280px]">
                        <img src="{{ $hero['img'] }}" alt="{{ $hero['title'] }}" class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 text-white">
                            <p class="font-playfair text-2xl font-bold">{{ $hero['title'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white border-y border-gray-100 py-24 px-4 sm:px-8">
        <div class="max-w-content mx-auto">
            <p class="text-sm font-semibold uppercase tracking-widest text-accent mb-3">Featured</p>
            <h2 class="font-playfair text-4xl md:text-5xl font-bold text-gray-900 mb-12">Blooms worth opening</h2>

            <div class="flex flex-wrap gap-8">
                @foreach ($bouquets as $item)
                    <div class="w-full sm:max-w-[320px]">
                        <x-bouquet-card
                            :name="$item->title"
                            :price="$item->price"
                            :href="route('bouquets.show', $item->id)"
                            :image="$item->galleryImageUrl()"
                        >
                            <x-slot name="badge">{{ $item->category?->name ?? 'Collection' }}</x-slot>
                            {{ $item->body }}
                        </x-bouquet-card>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-site-layout>
