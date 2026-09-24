<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Log Out') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('End your session on this device. You can sign in again anytime.') }}
        </p>
    </header>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <x-primary-button>
            {{ __('Log Out') }}
        </x-primary-button>
    </form>
</section>
