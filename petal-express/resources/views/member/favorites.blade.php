<x-member-page
    heading="Favorites"
    intro="A short list from the live showcase. Open any bouquet for details."
>
    @if ($bouquets->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-200 bg-white px-8 py-14 text-center">
            <p class="font-playfair text-xl font-semibold text-gray-900">Nothing saved yet</p>
            <p class="mt-3 text-sm text-gray-600">New bouquets are added in the showcase all the time.</p>
            <a href="{{ route('bouquets.index') }}" class="mt-8 inline-flex min-h-11 items-center text-sm font-semibold text-accent hover:underline">
                Go to showcase
            </a>
        </div>
    @else
        <ul class="space-y-6 sm:space-y-8">
            @foreach ($bouquets as $bouquet)
                <x-showcase-list-item
                    :href="route('bouquets.show', $bouquet)"
                    :title="$bouquet->title"
                    :image="$bouquet->displayImageUrl()"
                >
                    <x-slot name="meta">{{ $bouquet->category?->name ?? 'Collection' }}</x-slot>
                    <x-slot name="subline">{{ $bouquet->price }}</x-slot>
                </x-showcase-list-item>
            @endforeach
        </ul>
    @endif
</x-member-page>
