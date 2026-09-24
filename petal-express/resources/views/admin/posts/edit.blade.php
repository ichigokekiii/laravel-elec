<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">Edit bouquet</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-6 sm:p-8">
                    @if ($errors->any())
                        <div class="mb-5 rounded-[1.5rem] border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="title" value="Name" />
                            <x-text-input id="title" name="title" type="text" class="mt-1.5 block w-full" :value="old('title', $post->title)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="price" value="Price" />
                            <x-text-input id="price" name="price" type="text" class="mt-1.5 block w-full" :value="old('price', $post->price)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <div>
                            <x-input-label for="body" value="Description" />
                            <textarea id="body" name="body" rows="4" class="mt-1.5 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent/15" required>{{ old('body', $post->body) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('body')" />
                        </div>

                        <div class="flex items-center gap-3 pt-1">
                            <x-primary-button>Save changes</x-primary-button>
                            <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-accent">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
