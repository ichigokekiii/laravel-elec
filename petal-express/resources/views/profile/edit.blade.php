<x-app-layout shell="max-w-4xl">
    <x-slot name="header">
        <h2 class="font-playfair text-2xl font-bold text-gray-900 tracking-tight sm:text-3xl">
            {{ __('Profile') }}
        </h2>
        <p class="mt-3 text-center text-sm text-gray-600 sm:text-left">
            Update your name, email, and password for your Petal Express account.
        </p>
    </x-slot>

    <div class="px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
        <div class="max-w-4xl mx-auto w-full space-y-8">
            <x-admin-form-panel>
                @include('profile.partials.update-profile-information-form')
            </x-admin-form-panel>

            <x-admin-form-panel>
                @include('profile.partials.update-password-form')
            </x-admin-form-panel>

            <x-admin-form-panel>
                @include('profile.partials.logout-form')
            </x-admin-form-panel>
        </div>
    </div>
</x-app-layout>
