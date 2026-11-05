<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">Edit bouquet</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-admin-photo-preview-shell>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                    <div class="p-6 sm:p-8">
                        <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label for="title" value="Name" />
                                <x-text-input id="title" name="title" type="text" class="mt-1.5 block w-full" :value="old('title', $post->title)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="price" value="Price" />
                                <div class="mt-1.5 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center rounded-l-md border border-gray-300 bg-gray-50 px-3 text-sm font-semibold text-accent">₱</span>
                                    <x-text-input id="price" name="price" type="number" min="0" step="1" class="block w-full" :value="old('price', $post->priceForInput())" required />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>

                            <div>
                                <x-input-label for="category_id" value="Category" />
                                <select id="category_id" name="category_id" class="mt-1.5 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent/15" required>
                                    <option value="">Pick a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                            </div>

                            <div>
                                <x-input-label for="body" value="Description" />
                                <textarea id="body" name="body" rows="4" class="mt-1.5 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent/15" required>{{ old('body', $post->body) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('body')" />
                            </div>

                            <div>
                                <x-input-label for="image" value="Photo" />
                                @if ($post->image)
                                    <div class="mt-2 flex items-center gap-3">
                                        <x-admin-photo-thumb :src="$post->displayImageUrl()" :title="$post->title" size="lg" />
                                        <p class="text-xs text-gray-500">Click the photo to preview the watermark. Leave the file empty to keep this photo.</p>
                                    </div>
                                @endif
                                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-sm text-gray-700">
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG, or WebP. Max 3 MB.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('image')" />
                            </div>

                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <x-primary-button class="min-h-11">Save changes</x-primary-button>
                                <a href="{{ route('admin.posts.index') }}" class="inline-flex min-h-11 items-center text-sm text-gray-600 hover:text-accent">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </x-admin-photo-preview-shell>
        </div>
    </div>
</x-app-layout>
