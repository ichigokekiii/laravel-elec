<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">Add bouquet</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 rounded-[1.5rem] border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.posts.store') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="title" value="Name" />
                            <x-text-input id="title" name="title" type="text" class="mt-2 block w-full" :value="old('title')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="price" value="Price" />
                            <x-text-input id="price" name="price" type="text" class="mt-2 block w-full" :value="old('price')" placeholder="₱1,299" required />
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <div>
                            <x-input-label for="body" value="Description" />
                            <textarea id="body" name="body" rows="6" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent/15" required>{{ old('body') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('body')" />
                        </div>

                        <div class="flex gap-3">
                            <x-primary-button>Save bouquet</x-primary-button>
                            <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-accent">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
