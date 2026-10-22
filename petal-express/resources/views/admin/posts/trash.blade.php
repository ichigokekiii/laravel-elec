<x-admin-page title="Trash">
    <x-slot name="actions">
        <a
            href="{{ route('admin.posts.index') }}"
            class="inline-flex min-h-11 items-center text-sm font-medium text-gray-600 hover:text-accent"
        >
            Back to showcase
        </a>
    </x-slot>

    <x-admin-photo-preview-shell>
        <div class="space-y-8">
            <p class="text-center text-sm text-gray-600 sm:text-left">
                Soft-deleted bouquets. Restore or remove them permanently.
            </p>

            <ul class="space-y-6 sm:space-y-8">
                @forelse ($posts as $post)
                    <x-admin-post-list-item
                        :title="$post->title"
                        :image="$post->displayImageUrl()"
                        :image-title="$post->title"
                    >
                        <x-slot name="note">Deleted {{ $post->deleted_at->diffForHumans() }}</x-slot>

                        <x-slot name="actions">
                            <form method="POST" action="{{ route('admin.posts.restore', $post) }}" class="inline-flex">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="inline-flex min-h-11 items-center text-xs font-semibold uppercase tracking-wide text-accent hover:underline"
                                >
                                    Restore
                                </button>
                            </form>
                            <form
                                method="POST"
                                action="{{ route('admin.posts.force', $post) }}"
                                class="inline-flex"
                                onsubmit="return confirm('Delete this bouquet forever? This cannot be undone.')"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="inline-flex min-h-11 items-center text-xs font-semibold uppercase tracking-wide text-red-700 hover:underline"
                                >
                                    Delete forever
                                </button>
                            </form>
                        </x-slot>
                    </x-admin-post-list-item>
                @empty
                    <li class="rounded-2xl border border-dashed border-gray-200 bg-white px-8 py-14 text-center text-gray-600">
                        Trash is empty.
                    </li>
                @endforelse
            </ul>

            <div class="pt-2">
                {{ $posts->links() }}
            </div>
        </div>
    </x-admin-photo-preview-shell>
</x-admin-page>
