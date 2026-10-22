<x-app-layout shell="max-w-4xl">
    <x-slot name="header">
        <h2 class="font-playfair text-2xl font-bold text-gray-900 tracking-tight sm:text-3xl">Custom order</h2>
        <p class="mt-3 text-center text-sm text-gray-600 sm:text-left">
            Tell us what you have in mind. We will email you with options and timing.
        </p>
    </x-slot>

    <div class="px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
        <div class="max-w-4xl mx-auto w-full">
            <x-admin-form-panel>
                <form method="POST" action="{{ route('posts.submit.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="title" value="Occasion or set name" />
                        <x-text-input id="title" name="title" type="text" class="mt-2 block w-full" :value="old('title')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="body" value="Details" />
                        <textarea id="body" name="body" rows="6" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent/15" required placeholder="Colors, budget, delivery date…">{{ old('body') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('body')" />
                    </div>

                    <div class="pt-2">
                        <x-primary-button class="min-h-11">Send request</x-primary-button>
                    </div>
                </form>
            </x-admin-form-panel>
        </div>
    </div>
</x-app-layout>
