<x-site-layout title="Services">
    <section class="relative isolate overflow-hidden border-b border-gray-100 bg-page pt-20 pb-16 px-6 md:px-12">
        <img src="https://images.unsplash.com/photo-1487530811176-3780de880c2d?q=80&w=1600" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover blur-[1px] scale-[1.02]">
        <div class="absolute inset-0 -z-10 bg-white/25"></div>

        <div class="max-w-3xl">
            <x-page-banner
                eyebrow="What we offer"
                title="Care for"
                script="Every Moment"
            >
                <p class="text-gray-800">Same-day delivery, custom arrangements, and simple member tracking once you sign in.</p>
            </x-page-banner>
        </div>
    </section>

    <section class="bg-page py-24 px-6 md:px-12">
        <div class="max-w-content mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([
                'Same-day bouquet delivery in selected Metro Manila areas',
                'Custom arrangements for events and school orgs',
                'Add-on cards, chocolates, and simple gift wrapping',
                'Member tracking for orders and saved favorites',
            ] as $service)
                <div class="rounded-[2rem] border border-gray-100 bg-white p-8 hover:shadow-xl transition">
                    <p class="font-playfair text-xl font-semibold text-gray-900 leading-snug">{{ $service }}</p>
                </div>
            @endforeach
        </div>
    </section>
</x-site-layout>
