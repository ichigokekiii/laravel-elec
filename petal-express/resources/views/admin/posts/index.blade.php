<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">Showcase</h2>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.posts.trash') }}" class="inline-flex min-h-11 shrink-0 items-center rounded-full border border-gray-200 bg-white px-5 text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-accent transition">
                    Trash ({{ $trashedCount }})
                </a>
                <a
                    href="{{ route('admin.posts.create') }}"
                    class="inline-flex min-h-11 shrink-0 items-center rounded-full bg-gray-900 text-white px-6 text-xs font-semibold uppercase tracking-wide border-2 border-gray-900 hover:bg-transparent hover:text-gray-900 transition"
                >
                    Add bouquet
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-admin-photo-preview-shell>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                    <div class="p-6 sm:p-8 text-gray-900 space-y-3">
                        @forelse ($posts as $post)
                            <div class="rounded-[1.5rem] border border-gray-100 px-4 py-3 sm:px-5 flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <x-admin-photo-thumb :src="$post->displayImageUrl()" :title="$post->title" />
                                    <div class="min-w-0">
                                        <p class="font-playfair text-base sm:text-lg font-semibold truncate">{{ $post->title }}</p>
                                        <p class="text-sm text-accent font-semibold">{{ $post->price }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $post->category?->name ?? 'No category' }}
                                            @if ($post->user)
                                                · Added by {{ $post->user->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-4 shrink-0">
                                    <a href="{{ route('bouquets.show', $post) }}" class="inline-flex items-center min-h-11 text-xs font-semibold uppercase tracking-wide text-gray-600 hover:text-accent">View</a>
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="inline-flex items-center min-h-11 text-xs font-semibold uppercase tracking-wide text-accent hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center min-h-11 text-xs font-semibold uppercase tracking-wide text-red-700 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600">No bouquets in the showcase yet.</p>
                        @endforelse
                    </div>
                </div>
            </x-admin-photo-preview-shell>

            {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>
