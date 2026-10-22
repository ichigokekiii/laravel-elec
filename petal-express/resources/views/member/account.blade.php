<x-member-page
    heading="Account"
    intro="Profile and sign-in for your Petal Express member area."
>
    <dl class="rounded-2xl border border-gray-200 bg-white divide-y divide-gray-200">
        <div class="p-6 sm:p-8 sm:flex sm:items-center sm:gap-6">
            <dt class="sr-only">Avatar</dt>
            <dd class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-accent/10 text-lg font-bold text-accent">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </dd>
            <div class="mt-4 sm:mt-0 min-w-0 flex-1 grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Name</dt>
                    <dd class="mt-1 font-playfair text-xl font-semibold text-gray-900">{{ Auth::user()->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-800 break-all">{{ Auth::user()->email }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Role</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ Auth::user()->isAdmin() ? 'Shop admin' : 'Member' }}</dd>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 flex flex-wrap gap-3">
            <a
                href="{{ route('profile.edit') }}"
                class="inline-flex min-h-11 items-center rounded-full bg-gray-900 px-8 text-xs font-semibold uppercase tracking-wide text-white border-2 border-gray-900 transition active:scale-[0.98] hover:bg-transparent hover:text-gray-900"
            >
                Edit profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="inline-flex min-h-11 items-center rounded-full border border-gray-300 bg-white px-8 text-xs font-semibold uppercase tracking-wide text-gray-700 transition active:scale-[0.98] hover:border-accent hover:text-accent"
                >
                    Log out
                </button>
            </form>
        </div>
    </dl>

    @if (Auth::user()->isAdmin())
        <p class="mt-6 text-sm text-gray-600">
            <a href="{{ route('admin.posts.index') }}" class="font-semibold text-accent hover:underline">Manage showcase bouquets</a>
        </p>
    @endif
</x-member-page>
