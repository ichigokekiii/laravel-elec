<x-site-layout :title="$bouquet->title">
    <section class="bg-page pt-24 md:pt-28 pb-24 px-6 md:px-12">
        <div class="max-w-4xl mx-auto grid gap-12 lg:grid-cols-2 lg:items-center lg:gap-16">
            <div class="overflow-hidden rounded-[2.5rem] border border-gray-100">
                <img src="{{ $bouquet->displayImageUrl() }}" alt="{{ $bouquet->title }}" class="h-full w-full object-cover min-h-[420px]">
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-accent mb-3">Petal Express</p>
                <h1 class="font-playfair text-5xl md:text-6xl font-bold text-gray-900 leading-tight">{{ $bouquet->title }}</h1>
                <p class="mt-4 text-2xl font-semibold text-accent">{{ $bouquet->price }}</p>
                <p class="mt-3 text-sm text-gray-600">
                    {{ $bouquet->category?->name ?? 'Uncategorized' }}
                    @if ($bouquet->user)
                        · Added by {{ $bouquet->user->name }}
                    @endif
                </p>
                <p class="mt-6 text-base md:text-lg font-light leading-relaxed text-gray-600">{{ $bouquet->body }}</p>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('bouquets.index') }}" class="inline-flex min-h-11 items-center rounded-full bg-gray-900 text-white px-8 text-xs font-semibold uppercase tracking-wide border-2 border-gray-900 hover:bg-transparent hover:text-gray-900 transition">
                        Back to showcase
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex min-h-11 items-center rounded-full border border-gray-200 bg-white px-8 text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-accent transition">
                        Ask about this set
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-site-layout>
