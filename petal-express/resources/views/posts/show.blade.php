<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">{{ $post->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <img src="{{ $post->galleryImageUrl() }}" alt="{{ $post->title }}" class="h-64 w-full object-cover">
                <div class="p-8 text-gray-900 space-y-4">
                    <p class="text-xl font-semibold text-accent">{{ $post->price }}</p>
                    <p class="text-sm text-gray-600">
                        {{ $post->category?->name ?? 'Uncategorized' }}
                        @if ($post->user)
                            · Added by {{ $post->user->name }}
                        @endif
                    </p>
                    <p class="font-light leading-relaxed whitespace-pre-line">{{ $post->body }}</p>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ route('bouquets.index') }}" class="inline-flex text-sm font-semibold text-accent hover:underline">Browse showcase</a>
                        <a href="{{ route('posts.submit') }}" class="inline-flex text-sm font-semibold text-gray-600 hover:text-accent">Request a custom set</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
