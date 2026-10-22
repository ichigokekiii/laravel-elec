<x-member-page
    heading="Favorites"
    intro="A short list from the live showcase. Open any bouquet for details."
>
    @if ($bouquets->isEmpty())
        <div class="rounded-xl border border-gray-200 bg-white px-6 py-10 text-left">
            <p class="font-playfair text-xl font-semibold text-gray-900">Nothing saved yet</p>
            <p class="mt-2 text-sm text-gray-600">New bouquets are added in the showcase all the time.</p>
            <a href="{{ route('bouquets.index') }}" class="mt-6 inline-flex min-h-11 items-center text-sm font-semibold text-accent hover:underline">
                Go to showcase
            </a>
        </div>
    @else
        <ul class="space-y-3">
            @foreach ($bouquets as $bouquet)
                <li>
                    <a
                        href="{{ route('bouquets.show', $bouquet) }}"
                        class="group flex gap-4 rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 transition hover:border-accent/25 active:scale-[0.99]"
                    >
                        <img
                            src="{{ $bouquet->displayImageUrl() }}"
                            alt=""
                            class="h-20 w-20 shrink-0 rounded-xl object-cover border border-gray-100"
                        >
                        <div class="min-w-0 flex-1 flex flex-col justify-center">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                {{ $bouquet->category?->name ?? 'Collection' }}
                            </p>
                            <p class="mt-1 font-playfair text-lg font-semibold text-gray-900 group-hover:text-accent transition-colors truncate">
                                {{ $bouquet->title }}
                            </p>
                            <p class="mt-1 text-sm font-semibold text-accent">{{ $bouquet->price }}</p>
                        </div>
                        <span class="hidden sm:flex shrink-0 items-center text-xs font-semibold uppercase tracking-wide text-gray-400 group-hover:text-accent">
                            View
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</x-member-page>
