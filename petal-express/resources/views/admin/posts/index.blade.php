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
        <div class="space-y-4">
            <p class="mx-auto max-w-3xl text-center text-sm text-gray-600 sm:text-left">
                Manage showcase bouquets, photos, and pricing. Newest items appear first.
            </p>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-gray-200 bg-page text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold sm:px-6" scope="col">Bouquet</th>
                            <th class="hidden md:table-cell px-4 py-3 font-semibold" scope="col">Category</th>
                            <th class="px-4 py-3 font-semibold sm:px-6" scope="col">Price</th>
                            <th class="px-4 py-3 font-semibold text-right sm:px-6" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($posts as $post)
                            <tr class="transition hover:bg-page/60">
                                <td class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <x-admin-photo-thumb :src="$post->displayImageUrl()" :title="$post->title" />
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-900 truncate">{{ $post->title }}</p>
                                            <p class="mt-0.5 text-xs text-gray-500 md:hidden">{{ $post->category?->name ?? 'No category' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell px-4 py-4 text-gray-600">
                                    {{ $post->category?->name ?? '—' }}
                                </td>
                                <td class="px-4 py-4 font-semibold text-accent sm:px-6 whitespace-nowrap">
                                    {{ $post->price }}
                                </td>
                                <td class="px-4 py-4 sm:px-6">
                                    <div class="flex flex-wrap items-center justify-end gap-3 text-xs font-semibold uppercase tracking-wide">
                                        <a href="{{ route('bouquets.show', $post) }}" class="text-gray-600 hover:text-accent">View</a>
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="text-accent hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-700 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-left text-gray-600">
                                    No bouquets yet.
                                    <a href="{{ route('admin.posts.create') }}" class="ml-1 font-semibold text-accent hover:underline">Add the first one</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $posts->links() }}
            </div>
        </div>
    </x-admin-photo-preview-shell>
</x-admin-page>
