<nav x-data="{ open: false }" class="sticky top-0 z-40 w-full border-b border-gray-200 bg-[#ffffffee] backdrop-blur-md">
    <div class="max-w-7xl mx-auto flex items-center gap-3 px-4 py-3 md:gap-6 md:px-8 md:py-4">
        <div class="flex flex-1 items-center justify-start min-w-0">
            <a href="{{ route('home') }}" class="text-lg font-semibold tracking-wide text-brand lowercase shrink-0">
                petal express
            </a>
        </div>

        <div class="hidden md:flex flex-1 justify-center gap-6 lg:gap-8 text-sm text-gray-700">
            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'text-accent' : 'hover:text-accent' }}">My Orders</a>
            <a href="{{ route('favorites.index') }}" class="{{ request()->routeIs('favorites.*') ? 'text-accent' : 'hover:text-accent' }}">Favorites</a>
            <a href="{{ route('account.edit') }}" class="{{ request()->routeIs('account.*') ? 'text-accent' : 'hover:text-accent' }}">Account</a>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'text-accent' : 'hover:text-accent' }}">Showcase</a>
            @endif
            @if (Auth::user()->isUser())
                <a href="{{ route('posts.submit') }}" class="{{ request()->routeIs('posts.submit*') ? 'text-accent' : 'hover:text-accent' }}">Custom order</a>
            @endif
        </div>

        <div class="flex flex-1 items-center justify-end gap-3 min-w-0">
            <button type="button" class="md:hidden p-2 text-gray-700" @click="open = ! open" aria-label="Menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="hidden sm:block">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:text-accent focus:outline-none transition">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-4 w-4 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <div class="md:hidden border-t border-gray-100 divide-y divide-gray-100 bg-white" x-show="open" x-cloak>
        <a href="{{ route('orders.index') }}" class="block px-4 py-3 min-h-11 text-sm text-gray-700">My Orders</a>
        <a href="{{ route('favorites.index') }}" class="block px-4 py-3 min-h-11 text-sm text-gray-700">Favorites</a>
        <a href="{{ route('account.edit') }}" class="block px-4 py-3 min-h-11 text-sm text-gray-700">Account</a>
        @if (Auth::user()->isAdmin())
            <a href="{{ route('admin.posts.index') }}" class="block px-4 py-3 min-h-11 text-sm text-gray-700">Showcase</a>
        @endif
        @if (Auth::user()->isUser())
            <a href="{{ route('posts.submit') }}" class="block px-4 py-3 min-h-11 text-sm text-gray-700">Custom order</a>
        @endif
        <a href="{{ route('profile.edit') }}" class="block px-4 py-3 min-h-11 text-sm text-gray-700">Profile</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-4 py-3 min-h-11 text-sm text-gray-700">Log Out</button>
        </form>
    </div>
</nav>
