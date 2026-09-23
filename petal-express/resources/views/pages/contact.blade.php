<x-site-layout title="Contact">
    <section class="bg-page pt-24 md:pt-28 pb-24 px-6 md:px-12">
        <div class="max-w-content mx-auto grid gap-12 lg:grid-cols-2">
            <div>
                <x-page-banner
                    eyebrow="Say hello"
                    title="Contact"
                    script="Petal Express"
                >
                    <p>Ask about delivery areas, custom orders, or school event packages.</p>
                </x-page-banner>
            </div>

            <div>
                @if (session('status'))
                    <div class="mb-6 rounded-[1.5rem] border border-accent/20 bg-accent-soft px-5 py-4 text-accent">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="rounded-[2rem] border border-gray-100 bg-white p-8 shadow-sm">
                    {{-- @csrf is required on every POST form --}}
                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="name" value="Name" class="text-[10px] font-bold uppercase tracking-widest text-gray-500" />
                            <x-text-input id="name" name="name" type="text" class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-accent focus:ring-accent/15" :value="old('name')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Email" class="text-[10px] font-bold uppercase tracking-widest text-gray-500" />
                            <x-text-input id="email" name="email" type="email" class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-accent focus:ring-accent/15" :value="old('email')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="message" value="Message" class="text-[10px] font-bold uppercase tracking-widest text-gray-500" />
                            <textarea
                                id="message"
                                name="message"
                                rows="5"
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-accent focus:ring-accent/15"
                                required
                            >{{ old('message') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('message')" />
                        </div>

                        <x-primary-button class="w-full">
                            Send message
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-site-layout>
