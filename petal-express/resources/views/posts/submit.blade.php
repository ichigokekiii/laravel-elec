<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">Custom order</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-[1.5rem] border border-accent/20 bg-accent-soft px-5 py-4 text-accent">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-8">
                    <p class="mb-6 text-gray-600 font-light">
                        Tell us what you have in mind. We will email you with options and timing.
                    </p>

                    @if ($errors->any())
                        <div class="mb-6 rounded-[1.5rem] border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('posts.submit.store') }}" class="space-y-5">
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

                        <x-primary-button>Send request</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
