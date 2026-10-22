<x-app-layout shell="max-w-4xl">
    <x-slot name="header">
        <h2 class="font-playfair text-2xl font-bold text-gray-900 tracking-tight sm:text-3xl">{{ $post->title }}</h2>
    </x-slot>

    <div class="px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
        <div class="max-w-4xl mx-auto w-full space-y-8">
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
                <img src="{{ $post->galleryImageUrl() }}" alt="{{ $post->title }}" class="h-72 w-full object-cover sm:h-80">
                <div class="space-y-5 p-8 sm:p-10 text-gray-900">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        {{ $post->category?->name ?? 'Uncategorized' }}
                        @if ($post->user)
                            · Added by {{ $post->user->name }}
                        @endif
                    </p>
                    <p class="text-2xl font-semibold text-accent">{{ $post->price }}</p>
                    <p class="font-light leading-relaxed whitespace-pre-line text-gray-700">{{ $post->body }}</p>
                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ route('bouquets.index') }}" class="inline-flex min-h-11 items-center text-sm font-semibold text-accent hover:underline">Browse showcase</a>
                        <a href="{{ route('posts.submit') }}" class="inline-flex min-h-11 items-center text-sm font-semibold text-gray-600 hover:text-accent">Request a custom set</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
