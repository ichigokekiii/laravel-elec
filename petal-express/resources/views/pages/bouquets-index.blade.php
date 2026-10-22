<x-site-layout title="Showcase">
    <section class="relative isolate overflow-hidden border-b border-gray-100 bg-page pt-20 pb-20 px-6 md:px-12">
        <img src="{{ asset('images/bouquets/01.jpg') }}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover blur-[1px] scale-[1.02]">
        <div class="absolute inset-0 -z-10 bg-white/25"></div>

        <div class="max-w-4xl mx-auto">
            <x-page-banner
                eyebrow="The Collection"
                title="Blooms for"
                script="Every Moment"
            >
                <p class="text-gray-800">Browse our current sets. Tap a bouquet for details and delivery options.</p>
            </x-page-banner>
        </div>
    </section>

    <section class="bg-page py-20 px-6 md:px-12">
        <div class="max-w-4xl mx-auto space-y-10">
            <div class="flex flex-wrap gap-3">
                <a
                    href="{{ route('bouquets.index') }}"
                    class="inline-flex min-h-11 items-center gap-2 rounded-full px-5 py-2.5 text-sm {{ $activeCategory ? 'border border-gray-200 bg-white text-gray-700 hover:text-accent' : 'bg-accent text-white' }}"
                >
                    All
                    <span class="{{ $activeCategory ? 'text-gray-500' : 'text-white/80' }}">{{ $categories->sum('posts_count') }}</span>
                </a>
                @foreach ($categories as $category)
                    <a
                        href="{{ route('bouquets.index', ['category' => $category->id]) }}"
                        class="inline-flex min-h-11 items-center gap-2 rounded-full px-5 py-2.5 text-sm {{ $activeCategory === $category->id ? 'bg-accent text-white' : 'border border-gray-200 bg-white text-gray-700 hover:text-accent' }}"
                    >
                        {{ $category->name }}
                        <span class="{{ $activeCategory === $category->id ? 'text-white/80' : 'text-gray-500' }}">{{ $category->posts_count }}</span>
                    </a>
                @endforeach
            </div>

            <p class="text-center text-sm text-gray-600 sm:text-left">
                Newest bouquets first. Open any row for photos, pricing, and delivery options.
            </p>

            <ul class="space-y-6 sm:space-y-8">
                @forelse ($bouquets as $bouquet)
                    <x-showcase-list-item
                        :href="route('bouquets.show', $bouquet->id)"
                        :title="$bouquet->title"
                        :image="$bouquet->displayImageUrl()"
                    >
                        <x-slot name="meta">{{ $bouquet->category?->name ?? 'Collection' }}</x-slot>
                        <x-slot name="subline">{{ $bouquet->price }}</x-slot>
                    </x-showcase-list-item>
                @empty
                    <li class="rounded-2xl border border-dashed border-gray-200 bg-white px-8 py-14 text-center text-gray-600">
                        New blooms are on the way. Check back soon.
                    </li>
                @endforelse
            </ul>

            <div class="pt-4">
                {{ $bouquets->links() }}
            </div>
        </div>
    </section>
</x-site-layout>
