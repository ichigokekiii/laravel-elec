<x-site-layout title="Showcase">
    <section class="relative isolate overflow-hidden border-b border-gray-100 bg-page pt-20 pb-16 px-6 md:px-12">
        <img src="{{ asset('images/bouquets/01.jpg') }}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover blur-[1px] scale-[1.02]">
        <div class="absolute inset-0 -z-10 bg-white/25"></div>

        <div class="max-w-3xl">
            <x-page-banner
                eyebrow="The Collection"
                title="Blooms for"
                script="Every Moment"
            >
                <p class="text-gray-800">Browse our current sets. Tap a bouquet for details and delivery options.</p>
            </x-page-banner>
        </div>
    </section>

    <section class="bg-page py-16 px-6 md:px-12">
        <div class="max-w-content mx-auto grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($bouquets as $bouquet)
                <x-bouquet-card
                    :name="$bouquet->title"
                    :price="$bouquet->price"
                    :href="route('bouquets.show', $bouquet->id)"
                    :image="$bouquet->galleryImageUrl()"
                >
                    {{ $bouquet->body }}
                </x-bouquet-card>
            @empty
                <p class="text-gray-600 col-span-full">New blooms are on the way. Check back soon.</p>
            @endforelse
        </div>
    </section>
</x-site-layout>
