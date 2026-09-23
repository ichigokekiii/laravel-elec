<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">Favorites</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-8 text-gray-900">
                    <p class="mb-4 text-gray-600 font-light">Bouquets you saved for later.</p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-800">
                        @foreach ($favorites as $favorite)
                            <li>{{ $favorite }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
