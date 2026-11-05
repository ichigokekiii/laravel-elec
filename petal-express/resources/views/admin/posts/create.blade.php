<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">Add bouquet</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-8">
                    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="title" value="Name" />
                            <x-text-input id="title" name="title" type="text" class="mt-2 block w-full" :value="old('title')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="price" value="Price" />
                            <div class="mt-2 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center rounded-l-md border border-gray-300 bg-gray-50 px-3 text-sm font-semibold text-accent">₱</span>
                                <x-text-input id="price" name="price" type="number" min="0" step="1" class="block w-full" :value="old('price')" placeholder="1299" required />
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Numbers only. Saved with ₱ on the showcase.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <div>
                            <x-input-label for="category_id" value="Category" />
                            <select id="category_id" name="category_id" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent/15" required>
                                <option value="">Pick a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="body" value="Description" />
                            <textarea id="body" name="body" rows="6" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent/15" required>{{ old('body') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('body')" />
                        </div>

                        <div>
                            <x-input-label for="image" value="Photo" />
                            <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-sm text-gray-700" required />
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG, or WebP. Max 3 MB. Resized with the petal express watermark.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <x-primary-button class="min-h-11">Save bouquet</x-primary-button>
                            <a href="{{ route('admin.posts.index') }}" class="inline-flex min-h-11 items-center text-sm text-gray-600 hover:text-accent">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
