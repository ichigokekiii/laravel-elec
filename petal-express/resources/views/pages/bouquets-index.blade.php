<x-site-layout title="Showcase">
    <section class="relative isolate overflow-hidden border-b border-gray-100 bg-page pt-20 pb-16 px-6 md:px-12">
        <img src="https://images.unsplash.com/photo-1519378058457-4c29a0a2efac?q=80&w=1600" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover blur-[1px] scale-[1.02]">
        <div class="absolute inset-0 -z-10 bg-white/25"></div>

        <div class="max-w-3xl">
            <x-page-banner
                eyebrow="The Collection"
                title="Blooms for"
                script="Every Moment"
            >
                <p class="text-gray-800">Pick a set below. Each detail page uses a route parameter so the controller can load that bouquet.</p>
            </x-page-banner>
        </div>
    </section>

    <section class="bg-page py-16 px-6 md:px-12">
        <div class="max-w-content mx-auto grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($bouquets as $bouquet)
                <x-bouquet-card
                    :name="$bouquet['name']"
                    :price="$bouquet['price']"
                    :href="route('bouquets.show', $bouquet['id'])"
                    :image="$bouquet['image']"
                >
                    {{ $bouquet['description'] }}
                </x-bouquet-card>
            @endforeach
        </div>
    </section>
</x-site-layout>
