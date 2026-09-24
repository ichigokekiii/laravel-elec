<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">Showcase</h2>
            <a
                href="{{ route('admin.posts.create') }}"
                class="inline-flex shrink-0 rounded-full bg-gray-900 text-white px-6 py-3 text-xs font-semibold uppercase tracking-wide border-2 border-gray-900 hover:bg-transparent hover:text-gray-900 transition"
            >
                Add bouquet
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="rounded-[1.5rem] border border-accent/20 bg-accent-soft px-5 py-4 text-accent">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-6 sm:p-8 text-gray-900 space-y-3">
                    @forelse ($posts as $post)
                        <div class="rounded-[1.5rem] border border-gray-100 px-4 py-3 sm:px-5 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ $post->galleryImageUrl() }}" alt="" class="h-12 w-12 rounded-xl object-cover shrink-0">
                                <div class="min-w-0">
                                    <p class="font-playfair text-base sm:text-lg font-semibold truncate">{{ $post->title }}</p>
                                    <p class="text-sm text-accent font-semibold">{{ $post->price }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 shrink-0">
                                <a href="{{ route('bouquets.show', $post) }}" class="inline-flex items-center h-8 text-xs font-semibold uppercase tracking-wide text-gray-500 hover:text-accent">View</a>
                                <a href="{{ route('admin.posts.edit', $post) }}" class="inline-flex items-center h-8 text-xs font-semibold uppercase tracking-wide text-accent hover:underline">Edit</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">No bouquets in the showcase yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
