<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">Trash</h2>
            <a href="{{ route('admin.posts.index') }}" class="text-sm text-gray-600 hover:text-accent">Back to showcase</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-6 sm:p-8 text-gray-900 space-y-3">
                    @forelse ($posts as $post)
                        <div class="rounded-[1.5rem] border border-gray-100 px-4 py-3 sm:px-5 flex flex-wrap items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-playfair text-base sm:text-lg font-semibold truncate">{{ $post->title }}</p>
                                <p class="text-sm text-gray-600">Deleted {{ $post->deleted_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center gap-4 shrink-0">
                                <form method="POST" action="{{ route('admin.posts.restore', $post) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center h-8 text-xs font-semibold uppercase tracking-wide text-accent hover:underline">Restore</button>
                                </form>
                                <form method="POST" action="{{ route('admin.posts.force', $post) }}" onsubmit="return confirm('Delete this bouquet forever? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center h-8 text-xs font-semibold uppercase tracking-wide text-red-700 hover:underline">Delete forever</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">Trash is empty.</p>
                    @endforelse
                </div>
            </div>

            {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>
