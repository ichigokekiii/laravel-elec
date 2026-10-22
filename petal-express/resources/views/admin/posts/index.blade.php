<x-admin-page title="Showcase">
    <x-slot name="actions">
        <a
            href="{{ route('admin.posts.trash') }}"
            class="inline-flex min-h-11 items-center rounded-lg border border-gray-200 bg-white px-4 text-xs font-semibold uppercase tracking-wide text-gray-700 transition hover:border-accent/40 hover:text-accent"
        >
            Trash ({{ $trashedCount }})
        </a>
        <a
            href="{{ route('admin.posts.create') }}"
            class="inline-flex min-h-11 items-center rounded-lg bg-gray-900 px-5 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-accent active:scale-[0.98]"
        >
            Add bouquet
        </a>
    </x-slot>

    <x-admin-photo-preview-shell>
        <div class="space-y-8">
            <p class="text-center text-sm text-gray-600 sm:text-left">
                Manage showcase bouquets, photos, and pricing. Newest items appear first.
            </p>

            <ul class="space-y-6 sm:space-y-8">
                @forelse ($posts as $post)
                    <x-admin-post-list-item
                        :title="$post->title"
                        :image="$post->displayImageUrl()"
                        :image-title="$post->title"
                    >
                        <x-slot name="meta">{{ $post->category?->name ?? 'No category' }}</x-slot>
                        <x-slot name="subline">{{ $post->price }}</x-slot>

                        <x-slot name="actions">
                            <a
                                href="{{ route('bouquets.show', $post) }}"
                                class="inline-flex min-h-11 items-center text-xs font-semibold uppercase tracking-wide text-gray-500 hover:text-accent"
                            >
                                View
                            </a>
                            <a
                                href="{{ route('admin.posts.edit', $post) }}"
                                class="inline-flex min-h-11 items-center text-xs font-semibold uppercase tracking-wide text-accent hover:underline"
                            >
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="inline-flex min-h-11 items-center text-xs font-semibold uppercase tracking-wide text-red-700 hover:underline"
                                >
                                    Delete
                                </button>
                            </form>
                        </x-slot>
                    </x-admin-post-list-item>
                @empty
                    <li class="rounded-2xl border border-dashed border-gray-200 bg-white px-8 py-14 text-center text-gray-600">
                        No bouquets yet.
                        <a href="{{ route('admin.posts.create') }}" class="font-semibold text-accent hover:underline">Add the first one</a>.
                    </li>
                @endforelse
            </ul>

            <div class="pt-2">
                {{ $posts->links() }}
            </div>
        </div>
    </x-admin-photo-preview-shell>
</x-admin-page>
