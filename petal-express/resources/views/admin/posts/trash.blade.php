<x-admin-page title="Trash">
    <x-slot name="actions">
        <a href="{{ route('admin.posts.index') }}" class="text-sm font-medium text-gray-600 hover:text-accent">
            Back to showcase
        </a>
    </x-slot>

    <x-admin-photo-preview-shell>
        <p class="mb-4 mx-auto max-w-3xl text-center text-sm text-gray-600 sm:text-left">Soft-deleted bouquets. Restore or remove them permanently.</p>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-page text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3 sm:px-6" scope="col">Bouquet</th>
                        <th class="px-4 py-3 sm:px-6" scope="col">Deleted</th>
                        <th class="px-4 py-3 text-right sm:px-6" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-page/60">
                            <td class="px-4 py-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <x-admin-photo-thumb :src="$post->displayImageUrl()" :title="$post->title" />
                                    <span class="font-medium text-gray-900">{{ $post->title }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-gray-600 sm:px-6">{{ $post->deleted_at->diffForHumans() }}</td>
                            <td class="px-4 py-4 sm:px-6">
                                <div class="flex flex-wrap justify-end gap-3 text-xs font-semibold uppercase tracking-wide">
                                    <form method="POST" action="{{ route('admin.posts.restore', $post) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-accent hover:underline">Restore</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.posts.force', $post) }}" onsubmit="return confirm('Delete this bouquet forever? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-700 hover:underline">Delete forever</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-left text-gray-600">Trash is empty.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4">{{ $posts->links() }}</div>
    </x-admin-photo-preview-shell>
</x-admin-page>
