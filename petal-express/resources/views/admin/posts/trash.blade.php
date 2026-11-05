<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">Trash</h2>
            <a href="{{ route('admin.posts.index') }}" class="inline-flex min-h-11 items-center text-sm text-gray-600 hover:text-accent">Back to showcase</a>
        </div>
    </x-slot>

    <div
        class="py-12"
        x-data="{ deleteOpen: false, deleteTitle: '', deleteAction: '' }"
        @keydown.escape.window="deleteOpen = false"
    >
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
                                        <p class="text-sm text-gray-600">Deleted {{ $post->deleted_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-4 shrink-0">
                                    <form method="POST" action="{{ route('admin.posts.restore', $post) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center min-h-11 text-xs font-semibold uppercase tracking-wide text-accent hover:underline">Restore</button>
                                    </form>
                                    <button
                                        type="button"
                                        class="inline-flex items-center min-h-11 text-xs font-semibold uppercase tracking-wide text-red-700 hover:underline"
                                        @click="deleteTitle = @js($post->title); deleteAction = @js(route('admin.posts.force', $post)); deleteOpen = true"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600">Trash is empty.</p>
                        @endforelse
                    </div>
                </div>
            </x-admin-photo-preview-shell>

            {{ $posts->links() }}
        </div>

        <div
            x-show="deleteOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="delete-bouquet-title"
        >
            <div class="absolute inset-0 bg-gray-900" style="opacity: 0.8" @click="deleteOpen = false"></div>

            <form method="POST" x-bind:action="deleteAction" class="relative z-10 w-full max-w-md rounded-[2rem] border border-gray-100 bg-white p-6 shadow-xl sm:p-8">
                @csrf
                @method('DELETE')
                <h3 id="delete-bouquet-title" class="font-playfair text-2xl font-bold text-gray-900">Delete this bouquet?</h3>
                <p class="mt-3 text-sm text-gray-600">
                    <span class="font-semibold text-gray-900" x-text="deleteTitle"></span>
                    and its photo will be removed. This cannot be undone.
                </p>
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex min-h-11 items-center rounded-full border border-gray-200 bg-white px-5 text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-accent"
                        @click="deleteOpen = false"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="inline-flex min-h-11 items-center rounded-full bg-red-700 px-5 text-xs font-semibold uppercase tracking-wide text-white hover:bg-red-800"
                    >
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
