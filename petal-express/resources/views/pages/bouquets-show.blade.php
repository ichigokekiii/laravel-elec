<x-site-layout :title="$bouquet['name']">
    <section class="bg-page pt-24 md:pt-28 pb-24 px-6 md:px-12">
        <div class="max-w-content mx-auto grid gap-10 lg:grid-cols-2 lg:items-center">
            <div class="overflow-hidden rounded-[2.5rem] border border-gray-100">
                <img src="{{ $bouquet['image'] }}" alt="{{ $bouquet['name'] }}" class="h-full w-full object-cover min-h-[420px]">
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-accent mb-3">Bouquet #{{ $bouquet['id'] }}</p>
                <h1 class="font-playfair text-5xl md:text-6xl font-bold text-gray-900 leading-tight">{{ $bouquet['name'] }}</h1>
                <p class="mt-4 text-2xl font-semibold text-accent">{{ $bouquet['price'] }}</p>
                <p class="mt-6 text-base md:text-lg font-light leading-relaxed text-gray-600">{{ $bouquet['description'] }}</p>
                <p class="mt-4 text-sm text-gray-400">
                    This page got the id from the URL. The controller looked it up and passed the bouquet into this Blade view.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('bouquets.index') }}" class="inline-flex rounded-full bg-gray-900 text-white px-8 py-3 text-xs font-semibold uppercase tracking-wide border-2 border-gray-900 hover:bg-transparent hover:text-gray-900 transition">
                        Back to showcase
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex rounded-full border border-gray-200 bg-white px-8 py-3 text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-accent transition">
                        Ask about this set
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-site-layout>
