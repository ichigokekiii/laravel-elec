<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">Account</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-8 text-gray-900 space-y-4">
                    <p>
                        Signed in as Member ({{ Auth::user()->email }}).
                    </p>
                    <p class="text-gray-600 font-light">
                        Is this you? if yes, proceed
                    </p>
                    <a href="{{ route('profile.edit') }}" class="inline-flex rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white hover:bg-accent-hover transition">
                        Go to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
