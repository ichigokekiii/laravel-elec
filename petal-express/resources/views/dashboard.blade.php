<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-8 text-gray-900">
                    <p class="font-playfair text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}.</p>
                    <p class="mt-3 text-gray-600 font-light leading-relaxed">
                        Manage your account, orders, and favorites from here.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('orders.index') }}" class="inline-flex rounded-full bg-gray-900 text-white px-6 py-3 text-xs font-semibold uppercase tracking-wide border-2 border-gray-900 hover:bg-transparent hover:text-gray-900 transition">My Orders</a>
                <a href="{{ route('favorites.index') }}" class="inline-flex rounded-full border border-gray-200 bg-white px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-accent transition">Favorites</a>
                <a href="{{ route('account.edit') }}" class="inline-flex rounded-full border border-gray-200 bg-white px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-accent transition">Account</a>
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('admin.posts.index') }}" class="inline-flex rounded-full border border-gray-200 bg-white px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-accent transition">Showcase</a>
                @endif
                @if (Auth::user()->isUser())
                    <a href="{{ route('posts.submit') }}" class="inline-flex rounded-full border border-gray-200 bg-white px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-accent transition">Custom order</a>
                @endif
            </div>

            @if (Auth::user()->isUser() && isset($recentPosts) && $recentPosts->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                    <div class="p-8 text-gray-900 space-y-3">
                        <p class="font-playfair text-xl font-bold">From the showcase</p>
                        @foreach ($recentPosts as $post)
                            <a href="{{ route('posts.show', $post) }}" class="flex items-center gap-4 rounded-[1.5rem] border border-gray-100 px-5 py-3 hover:border-accent/30 transition">
                                <img src="{{ $post->galleryImageUrl() }}" alt="" class="h-12 w-12 rounded-xl object-cover">
                                <span>
                                    <span class="font-semibold block">{{ $post->title }}</span>
                                    <span class="text-sm text-accent">{{ $post->price }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
