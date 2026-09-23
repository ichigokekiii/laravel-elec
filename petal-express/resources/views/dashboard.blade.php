<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">
            Member dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-8 text-gray-900">
                    <p class="font-playfair text-2xl font-bold text-gray-900">Welcome back to petal express.</p>
                    <p class="mt-3 text-gray-600 font-light leading-relaxed">
                        You are logged in. Use the nav for My Orders, Favorites, or Account.
                        Guests who try those links get sent to login by the auth middleware.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('orders.index') }}" class="inline-flex rounded-full bg-gray-900 text-white px-6 py-3 text-xs font-semibold uppercase tracking-wide border-2 border-gray-900 hover:bg-transparent hover:text-gray-900 transition">My Orders</a>
                <a href="{{ route('favorites.index') }}" class="inline-flex rounded-full border border-gray-200 bg-white px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-accent transition">Favorites</a>
                <a href="{{ route('account.edit') }}" class="inline-flex rounded-full border border-gray-200 bg-white px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-accent transition">Account</a>
            </div>
        </div>
    </div>
</x-app-layout>
